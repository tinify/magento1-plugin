<?php
class Tiny_CompressImages_Block_Adminhtml_ApiNotification extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Check if the api key is set.
     *
     * @return bool
     */
    public function isApiKeySet()
    {
        $configHelper = Mage::helper('tiny_compressimages/config');

        if ($configHelper->getApiKey()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBackendUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/tiny_compressimages/');
    }
}