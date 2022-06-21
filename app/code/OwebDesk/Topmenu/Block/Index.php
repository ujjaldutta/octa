<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OwebDesk\Topmenu\Block;

class Index extends \Magento\Framework\View\Element\Template
{
/**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Image $imageHelper
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->imageHelper = $imageHelper;
        parent::__construct($context);
    }

    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' =>$ids]);
        return $collection;
    }

    public function catmenu($ids)
    { 
        $ids=explode(',',$ids);
        $categoryProducts = $this->getProductCollectionByCategories($ids);
        $link='';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        foreach ($categoryProducts as $product) {
            //$productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
            $productImageUrl = $this->imageHelper->init($product, 'product_small_image')->getUrl();
            $link .='<a href="'.$product->getProductUrl().'" class="image-container" 
            data-hover="'.$productImageUrl.'">'.$product->getName().'</a>';
           
        }
        
 

        return $link;
    }
}

