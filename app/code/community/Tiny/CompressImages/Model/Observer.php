<?php
class Tiny_CompressImages_Model_Observer
{
    /**
     * @var null|Tiny_CompressImages_Helper_Tinify
     */
    protected $_tinifyHelper = null;

    /**
     * Compress product images
     *
     * @param $observer
     *
     * @return $this
     */
    public function catalogProductImageSaveAfter($observer)
    {
        if ($this->_tinifyHelper === null) {
            $this->_tinifyHelper = Mage::helper('tiny_compressimages/tinify');
        }

        $storeId = Mage::app()->getStore()->getStoreId();
        $this->_tinifyHelper->setProductImage($observer->getObject(), $storeId)->compress();

        return $this;
    }
}
