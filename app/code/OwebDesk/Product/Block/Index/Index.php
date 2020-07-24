<?php

namespace OwebDesk\Product\Block\Index;


class Index extends \Magento\Framework\View\Element\Template {
    protected $_coreRegistry;
    public function __construct(\Magento\Catalog\Block\Product\Context $context, 
    \Magento\Framework\Registry $registry) {

        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }


    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

}