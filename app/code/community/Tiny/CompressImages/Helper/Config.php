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
class Tiny_CompressImages_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * Configuration XPATH
     */
    const XPATH_ENABLED              = 'tig_tinypng/settings/enabled';
    const XPATH_API_KEY              = 'tig_tinypng/settings/api_key';
    //const XPATH_PRODUCT_IMAGES_TYPES = 'tig_tinypng/settings/product_compression';
    const XPATH_IMAGE_TYPE_BASE     = 'tig_tinypng/settings/base_images';
    const XPATH_IMAGE_TYPE_SMALL     = 'tig_tinypng/settings/small_images';
    const XPATH_IMAGE_TYPE_THUMBNAIL = 'tig_tinypng/settings/thumbnails';
    const XPATH_IMAGE_TYPE_SWATCHES  = 'tig_tinypng/settings/swatches';
    const XPATH_LOGGING_MODE         = 'tig_tinypng/settings/logging_mode';

    /**
     * Return the enabled modus (off, test or live)
     *
     * @param null $store
     *
     * @return int
     */
    protected function _getEnabled($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_ENABLED, $store);
    }

    /**
     * Is the extension enabled
     *
     * @param null|int $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        $enabledValue = $this->_getEnabled($store);

        if ($enabledValue != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is the extension currently in test mode
     *
     * @param null|int $store
     *
     * @return bool
     */
    public function isTestMode($store = null)
    {
        $enabledValue = $this->_getEnabled($store);

        if ($enabledValue == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param null $store
     *
     * @return bool
     */
    public function isConfigured($store = null)
    {
        if (!$this->isEnabled($store)) {
            return false;
        }

        if (!$this->getApiKey($store)) {
            return false;
        }

        return true;
    }

    /**
     * Return the API Key
     *
     * @param null|int $store
     *
     * @return string
     */
    public function getApiKey($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_API_KEY, $store);
    }

    /**
     * Is automatic compression on or off.
     *
     * @param null $store
     *
     * @return mixed
     */
    public function isAutomaticCompressionEnabled($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_AUTO_COMPRESS, $store);
    }

    /**
     * Get the compress quality for TinyPng (min of 95 %)
     *
     * @param null $store
     *
     * @return mixed
     */
    public function getCompressQuality($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_COMPRESS_QUALITY, $store);
    }

    /**
     * Lets you know if base images should be compressed.
     *
     * @param null $store
     *
     * @return mixed
     */
    public function isBaseImageTypeEnabledForCompression($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_IMAGE_TYPE_BASE, $store);
    }

    /**
     * Lets you know if small images should be compressed.
     *
     * @param null $store
     *
     * @return mixed
     */
    public function isSmallImageTypeEnabledForCompression($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_IMAGE_TYPE_SMALL, $store);
    }

    /**
     * Lets you know if thumbnail-sized images should be compressed.
     *
     * @param null $store
     *
     * @return mixed
     */
    public function isThumbnailTypeEnabledForCompression($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_IMAGE_TYPE_THUMBNAIL, $store);
    }

    /**
     * Lets you know if swachtes (small images used to select product attributes) images should be compressed.
     *
     * @param null $store
     *
     * @return mixed
     */
    public function isSwatchTypeEnabledForCompression($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_IMAGE_TYPE_SWATCHES, $store);
    }


    /**
     * Returns an array of image types that can be compressed by TinyPNG
     *
     * @param null $store
     *
     * @return mixed
     */
    public function getProductImageTypesToCompress($store = null)
    {
        $imageTypes = array();

        if ($this->isBaseImageTypeEnabledForCompression($store)) {
            $imageTypes[] = 'image';
        }

        if ($this->isSmallImageTypeEnabledForCompression($store)) {
            $imageTypes[] = 'small_image';
        }

        if ($this->isThumbnailTypeEnabledForCompression($store)) {
            $imageTypes[] = 'thumbnail';
            $imageTypes[] = 'media_image';
        }

        if ($this->isSwatchTypeEnabledForCompression($store)) {
            $imageTypes[] = 'swatches';
        }

        return $imageTypes;
    }

    /**
     * Returns a comma separeted list of image types that can be compressed by TinyPNG
     *
     * @param null $store
     *
     * @return mixed
     */
    public function getCmsImageTypesToCompress($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_CMS_IMAGES_TYPES, $store);
    }

    /**
     * Returns the logging mode.
     *
     * @param null $store
     *
     * @return mixed
     */
    public function getLoggingMode($store = null)
    {
        return Mage::getStoreConfig(self::XPATH_LOGGING_MODE, $store);
    }
}
