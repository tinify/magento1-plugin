<?php
class Tiny_CompressImages_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{
    /**
     * Set the minimun required quality for CompressImages which is 95
     *
     * @var int
     */
    protected $_quality = 85;

    protected $_helper = null;

    /**
     * @return Tiny_CompressImages_Helper_Config
     */
    public function getHelper()
    {
        if ($this->_helper === null) {
            $this->_helper = Mage::helper('tiny_compressimages/config');
        }

        return $this->_helper;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        /** @var Tiny_CompressImages_Helper_Config $helper */
        $helper = $this->getHelper();
        if ($helper->isTestMode(Mage::app()->getStore()->getStoreId())) {
            return parent::getUrl();
        }

        $baseDir  = Mage::getBaseDir('media');
        $tinyPath = substr(Tiny_CompressImages_Helper_Tinify::TINY_COMPRESSIMAGES_MEDIA_DIRECTORY . DS, 1);

        $path = str_replace(
            $baseDir . DS,
            $tinyPath,
            $this->_newFile
        );

        if (!file_exists(Mage::getBaseDir(). '/' .str_replace(DS, '/', $path))) {
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
