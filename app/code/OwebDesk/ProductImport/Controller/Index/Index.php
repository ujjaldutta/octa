<?php

namespace OwebDesk\ProductImport\Controller\Index;
use OwebDesk\ProductImport\Service\ImportImageService;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $importimageservice;
    protected $videoGalleryProcessor;


public function __construct(
\Magento\Backend\App\Action\Context $context,
\OwebDesk\ProductImport\Service\ImportImageService $importimageservice,
\OwebDesk\ProductImport\Model\Product\Gallery\Video\Processor $videoGalleryProcessor
) {
$this->importimageservice = $importimageservice;
parent::__construct($context);
$this->videoGalleryProcessor = $videoGalleryProcessor;
}

    public function execute()
    {
        $mysqli = new \mysqli("localhost","root","rLHnkRNohWnLeL6ERs9M&WQvB3v","octawp");

        
        $query = "SELECT * FROM wp_octa_product_images  ORDER by id asc";

        if ($result = $mysqli->query($query)) {

            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
                $productGallery = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Gallery');
                $imageProcessor = $this->_objectManager->create('Magento\Catalog\Model\Product\Gallery\Processor');
                $galleryReadHandler = $this->_objectManager->create('Magento\Catalog\Model\Product\Gallery\ReadHandler');
                $product = $objectManager->create('Magento\Catalog\Model\Product')->load($row["entity_id"]);

                $galleryReadHandler->execute($product);
                $images = $product->getMediaGalleryImages();
                foreach($images as $child) {
                    $productGallery->deleteGallery($child->getValueId());
                    $imageProcessor->removeImage($product, $child->getFile());
                }

            }
        }


    $query = "SELECT * FROM wp_octa_product_videos  ORDER by id asc"; 
    $result = $mysqli->query($query);
    $video=[];
    while ($row = $result->fetch_assoc()) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $row['video_url'], $match);
        $youtube_id = $match[1];

        $vurl='https://www.youtube.com/watch?v='.$youtube_id.'&feature=youtu.be';
        '/pub/media/videothumb';
        $videoData = [
            'video_id' => $youtube_id, //set your video id
            'video_title' => "youtube", //set your video title
            'video_description' => "youtube video", //set your video description
            //'thumbnail' => '/media/videothumb/'.basename($row['placeholder']), //set your video thumbnail path.
            'video_provider' => "youtube",
            'video_metadata' => null,
            'video_url' => $vurl, //set your youtube channel's video url
            'media_type' => \Magento\ProductVideo\Model\Product\Attribute\Media\ExternalVideoEntryConverter::MEDIA_TYPE_CODE,
        ];
        //download thumbnail image and save locally under pub/media
        if(file_exists('/var/www/html/magento2/pub/media/'.basename($row['placeholder'])) && !empty(basename($row['placeholder']))){
            $videoData['file'] = basename($row['placeholder']);
        }else{
            $videoData['file'] = '168457.png';
        }
        

        $video[$row['entity_id']][]= $videoData;
        
    }
    foreach($video as $proid=>&$videoData){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($proid);
        $productRepository = $objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');

         
        $this->videoGalleryProcessor->addVideo(
            $product,
            $videoData,
            ['image', 'small_image', 'thumbnail'],
            false,
            false
        );
        $productRepository->save($product);
        
    }


    $mysqli = new \mysqli("localhost","root","rLHnkRNohWnLeL6ERs9M&WQvB3v","octawp");

        
    $query = "SELECT * FROM wp_octa_product_images  ORDER by id asc";

        if ($result = $mysqli->query($query)) {

            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($row["entity_id"]);
        $productRepository = $objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');
        $imagePath = $row["image"]; // path of the image
       
        try
                {
                   
                    
                    $status=$this->importimageservice->execute($product, $imagePath, $visible = true, $imageType = ['image', 'small_image', 'thumbnail']);
                    $price=$product->getPrice();
                    /*if($price==''){
                        $product->setPrice(0.00);
                    $productRepository->save($product);
                    echo $row["entity_id"].' id '.$row["id"].'<br />';
                    }*/
                    
                    
                    if($status){
                    $productRepository->save($product);
                    echo $row["entity_id"].' id '.$row["id"].'<br />';
                    }

                    
                    /*}else{
                        //$product->setPrice(0.00);
                    // $productRepository->save($product);
                    echo 'no price'.$row["entity_id"]. ' id '.$row["id"].'<br />';
                    }*/
                }
                catch (\Exception $e)
                {
                    echo 'not processed id '.$row["id"].$e->getMessage().'<br />';
                }
        
        
        
        
            }

    }



}
}