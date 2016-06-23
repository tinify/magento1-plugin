<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2016 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 */
class Tiny_CompressImages_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{
    /**
     * Set the minimun required quality for TinyPNG image compression which is 95
     *
     * @var int
     */
    protected $_quality = 85;

    /**
     * @return string
     */
    public function getUrl()
    {
        /** @var Tiny_CompressImages_Helper_Config $helper */
        $helper = Mage::helper('tig_tinypng/config');
        if ($helper->isTestMode(Mage::app()->getStore()->getStoreId())) {
            return parent::getUrl();
        }

        $baseDir  = Mage::getBaseDir('media');
        $tinyPath = substr(Tiny_CompressImages_Helper_Tinify::TINY_COMPRESSIMAGES_MEDIA_DIRECTORY.DS, 1);

        $path = str_replace(
            $baseDir . DS,
            $tinyPath,
            $this->_newFile
        );

        if (!file_exists(Mage::getBaseDir(). '/' .str_replace(DS, '/', $path))) {
            return parent::getUrl();
        }

        return Mage::getBaseUrl() . str_replace(DS, '/', $path);
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
