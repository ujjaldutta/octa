<?php
/**
 * Created By : Ujjal Dutta
 */
namespace OwebDesk\ProductImport\Model\Product\Gallery\Video;

use Magento\Framework\Exception\LocalizedException;

class Processor extends \Magento\Catalog\Model\Product\Gallery\Processor
{
    /**
     * @var \Magento\Catalog\Model\Product\Gallery\CreateHandler
     */
    protected $createHandler;

    /**
     * Processor constructor.
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param \Magento\MediaStorage\Helper\File\Storage\Database       $fileStorageDb
     * @param \Magento\Catalog\Model\Product\Media\Config              $mediaConfig
     * @param \Magento\Framework\Filesystem                            $filesystem
     * @param \Magento\Catalog\Model\ResourceModel\Product\Gallery     $resourceModel
     * @param \Magento\Catalog\Model\Product\Gallery\CreateHandler     $createHandler
     */
    public function __construct(
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Catalog\Model\Product\Media\Config $mediaConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Catalog\Model\ResourceModel\Product\Gallery $resourceModel,
        \Magento\Catalog\Model\Product\Gallery\CreateHandler $createHandler
    ) {
        parent::__construct($attributeRepository, $fileStorageDb, $mediaConfig, $filesystem, $resourceModel);
        $this->createHandler = $createHandler;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param array                          $videoData
     * @param [type]                         $mediaAttribute
     * @param boolean                        $move
     * @param boolean                        $exclude
     */
    public function addVideo(
        \Magento\Catalog\Model\Product $product,
        array $videoDataItems,
        $mediaAttribute = null,
        $move = false,
        $exclude = true
    )
    {
        foreach($videoDataItems as &$videoData){
        $file = $this->mediaDirectory->getRelativePath($videoData['file']);
        if (!$this->mediaDirectory->isFile($file))
        {
            
            throw new LocalizedException(__('The image does not exist.'.$file));
        }

        $pathinfo = pathinfo($file);
        $imgExtensions = ['jpg', 'jpeg', 'gif', 'png'];
        if (!isset($pathinfo['extension']) || !in_array(strtolower($pathinfo['extension']), $imgExtensions))
        {
            throw new LocalizedException(__('Please correct the image file type.'));
        }
        
        $fileName = \Magento\MediaStorage\Model\File\Uploader::getCorrectFileName($pathinfo['basename']);
        $dispretionPath = \Magento\MediaStorage\Model\File\Uploader::getDispretionPath($fileName);
        $fileName = $dispretionPath . '/' . $fileName;
        
        $fileName = $this->getNotDuplicatedFilename($fileName, $dispretionPath);

        $destinationFile = $this->mediaConfig->getTmpMediaPath($fileName);

        try {
            /** @var $storageHelper \Magento\MediaStorage\Helper\File\Storage\Database */
            $storageHelper = $this->fileStorageDb;
            if ($move)
            {
                $this->mediaDirectory->renameFile($file, $destinationFile);

                //Here, filesystem should be configured properly
                $storageHelper->saveFile($this->mediaConfig->getTmpMediaShortUrl($fileName));
            }
            else
            {
                $this->mediaDirectory->copyFile($file, $destinationFile);

                $storageHelper->saveFile($this->mediaConfig->getTmpMediaShortUrl($fileName));
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__('We couldn\'t move this file: %1.', $e->getMessage()));
        }

        $fileNames []= str_replace('\\', '/', $fileName);
        unset($videoData['file']);
        }

        
        $attrCode = $this->getAttribute()->getAttributeCode();
        $mediaGalleryData = $product->getData($attrCode);
       $position=0;

for( $i = 0;$i<count($videoDataItems);$i++){
        if (!is_array($mediaGalleryData))
        {
            $mediaGalleryData = ['images' => []];
        }

        foreach ($mediaGalleryData['images'] as &$image)
        {
            if (isset($image['position']) && $image['position'] > $position)
            {
                $position = $image['position'];
            }
        }

        $position++;

   
            $mediaGalleryData['images'][] = array_merge([
                'file' => $fileNames[$i],
                'label' => $videoDataItems[$i]['video_title'],
                'position' => $position,
                'disabled' => (int)$exclude
            ], $videoDataItems[$i]);
        
        

        if ($mediaAttribute !== null && isset($fileNames[$i]))
        {
            $product->setMediaAttribute($product, $mediaAttribute, $fileNames[$i]);
        }
    }
    if(count($mediaGalleryData['images'])>0){
        $product->setData($attrCode, $mediaGalleryData);
        $this->createHandler->execute($product);
    }

        

        
   

        return $fileName;
    }
}