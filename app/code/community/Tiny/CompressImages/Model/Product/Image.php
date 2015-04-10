<?php

class Tiny_CompressImages_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{

    /* Changed for TinyPNG image compression */
    protected $_quality = 100;

    /**
     * @return Mage_Catalog_Model_Product_Image
     */
    public function saveFile()
    {
        $filename = $this->getNewFile();
        $this->getImageProcessor()->save($filename);
        Mage::helper('core/file_storage_database')->saveFile($filename);

        /* Added for TinyPNG image compression */
        Mage::dispatchEvent('catalog_product_image_save_after', array($this->_eventObject => $this));

        return $this;
    }

}
