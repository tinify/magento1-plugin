<?php
class Tiny_CompressImages_Model_System_Config_Backend_ApiKey extends Mage_Core_Model_Config_Data
{
    /**
     * Validate the value before saving.
     *
     * @return Mage_Core_Model_Abstract
     * @throws Mage_Exception
     */
    protected function _beforeSave()
    {
        $apiKeyValue = $this->getValue();
        $helper = Mage::helper('tiny_compressimages/tinify');

        if (strlen($apiKeyValue) > 0) {
            $validateResult = $helper->validate($apiKeyValue);

            if (!$validateResult) {
                throw new Mage_Exception($helper->__('The Api Key is invalid'));
            }
        }

        return parent::_beforeSave();
    }
}
