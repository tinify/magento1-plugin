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
     * @var null|Tiny_CompressImages_Helper_Config
     */
    protected $_configHelper = null;

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
        if (!$this->getConfigHelper()->isEnabled()) {
            return $this;
        }

        try {
            $storeId = Mage::app()->getStore()->getStoreId();
            $this->getTinifyHelper()->setProductImage($observer->getObject(), $storeId)->compress();
        } catch (Exception $e) {
            $this->getDataHelper()->log($e);

            throw $e;
        }

        return $this;
    }

    /**
     * @return Tiny_CompressImages_Helper_Tinify
     */
    protected function getTinifyHelper()
    {
        if ($this->_tinifyHelper === null) {
            $this->_tinifyHelper = Mage::helper('tiny_compressimages/tinify');
        }

        return $this->_tinifyHelper;
    }

    /**
     * @return Tiny_CompressImages_Helper_Data
     */
    protected function getDataHelper()
    {
        if ($this->_dataHelper === null) {
            $this->_dataHelper = Mage::helper('tiny_compressimages');
        }

        return $this->_dataHelper;
    }

    /**
     * @return Tiny_CompressImages_Helper_Config
     */
    protected function getConfigHelper()
    {
        if ($this->_configHelper === null) {
            $this->_configHelper = Mage::helper('tiny_compressimages/config');
        }

        return $this->_configHelper;
    }
}
