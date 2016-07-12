<?php
class Tiny_CompressImages_Model_System_Config_Backend_Enabled extends Mage_Core_Model_Config_Data
{
    /**
     * Check if the mode is changed. If it was test mode, we delete all models.
     *
     * @return Mage_Core_Model_Abstract
     * @throws Mage_Exception
     */
    protected function _beforeSave()
    {
        return parent::_beforeSave();
    }
}
