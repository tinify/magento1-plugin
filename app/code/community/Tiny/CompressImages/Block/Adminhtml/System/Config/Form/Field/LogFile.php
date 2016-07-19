<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_LogFile extends Varien_Data_Form_Element_Abstract
{
    /**
     * Template file used
     *
     * @var string
     */
    protected $_template = 'Tiny/CompressImages/system/config/form/field/log_file.phtml';

    /**
     * Get the URL where we can download the log file.
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/CompressImagesAdminhtml_config/downloadLogs');
    }

    /**
     * Helper to check if the log file exists.
     *
     * @return bool
     */
    public function logFileExists()
    {
        return Mage::helper('tiny_compressimages')->getLogFileExists();
    }
}
