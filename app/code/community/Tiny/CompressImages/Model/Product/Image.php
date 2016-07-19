<?php
class Tiny_CompressImages_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{
    /**
     * Set the minimun required quality for CompressImages which is 95
     *
     * @var int
     */
    protected $_quality = 85;

    /**
     * @var Tiny_CompressImages_Helper_Config|null
     */
    protected $_configHelper = null;

    /**
     * @var Tiny_CompressImages_Helper_Tinify|null
     */
    protected $_dataHelper = null;

    /**
     * @return Tiny_CompressImages_Helper_Config
     */
    public function getConfigHelper()
    {
        if ($this->_configHelper === null) {
            $this->_configHelper = Mage::helper('tiny_compressimages/config');
        }

        return $this->_configHelper;
    }

    /**
     * @return Tiny_CompressImages_Helper_Data
     */
    public function getDataHelper()
    {
        if ($this->_dataHelper === null) {
            $this->_dataHelper = Mage::helper('tiny_compressimages');
        }

        return $this->_dataHelper;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        /** @var Tiny_CompressImages_Helper_Config $helper */
        $helper = $this->getConfigHelper();
        if ($helper->isTestMode(Mage::app()->getStore()->getStoreId())) {
            return parent::getUrl();
        }

        /** @var Tiny_Compressimages_Model_Image $model */
        $path = substr($this->_newFile, strlen(Mage::getBaseDir('media')) - 6);
        $model = Mage::getModel('tiny_compressimages/image')->load($path, 'path');
        if (!$model->getId() || !file_exists($model->getFilepathOptimized())) {
            return parent::getUrl();
        }

        return $model->getUrl();
    }

    /**
     * @return Mage_Catalog_Model_Product_Image $this
     */
    public function saveFile()
    {
        parent::saveFile();

        Mage::dispatchEvent('catalog_product_image_save_after', array($this->_eventObject => $this));

        return $this;
    }
}
