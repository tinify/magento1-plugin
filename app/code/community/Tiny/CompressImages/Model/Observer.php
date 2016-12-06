<?php
class Tiny_CompressImages_Model_Observer
{
    /**
     * @var null|Tiny_CompressImages_Helper_Tinify
     */
    protected $_tinifyHelper = null;

    /**
     * @var null|Tiny_CompressImages_Helper_Data
     */
    protected $_dataHelper = null;

    /**
     * Compress product images
     *
     * @param $observer
     *
     * @return $this
     * @throws Exception
     */
    public function catalogProductImageSaveAfter($observer)
    {
        if ($this->_tinifyHelper === null) {
            $this->_tinifyHelper = Mage::helper('tiny_compressimages/tinify');
        }

        if ($this->_dataHelper === null) {
            $this->_dataHelper = Mage::helper('tiny_compressimages');
        }

        try {
            $storeId = Mage::app()->getStore()->getStoreId();
            $this->_tinifyHelper->setProductImage($observer->getObject(), $storeId)->compress();
        } catch (Exception $e) {
            $this->_dataHelper->log($e);

            throw $e;
        }

        return $this;
    }
}
