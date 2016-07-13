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

        $path = $this->getDataHelper()->getImagePath($this->_newFile);
        if (!file_exists(Mage::getBaseDir() . '/' . str_replace(DS, '/', $path))) {
            return parent::getUrl();
        }

        $url =  str_replace(DS, '/', $path);

        if (strpos($url, 'media/') === 0) {
            $url = substr($url, 6);
        }

        return Mage::getBaseUrl('media') . $url;
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
