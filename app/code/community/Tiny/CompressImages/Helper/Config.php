<?php
class Tiny_CompressImages_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * Configuration XPATH
     */
    const XPATH_ENABLED              = 'tiny_compressimages/advanced/enabled';
    const XPATH_API_KEY              = 'tiny_compressimages/settings/api_key';
    const XPATH_IMAGE_TYPE_BASE      = 'tiny_compressimages/image_types/base_images';
    const XPATH_IMAGE_TYPE_SMALL     = 'tiny_compressimages/image_types/small_images';
    const XPATH_IMAGE_TYPE_THUMBNAIL = 'tiny_compressimages/image_types/thumbnails';
    const XPATH_IMAGE_TYPE_SWATCHES  = 'tiny_compressimages/image_types/swatches';
    const XPATH_LOGGING_MODE         = 'tiny_compressimages/advanced/logging_mode';

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
     * Returns an array of image types that can be compressed by CompressImages
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
