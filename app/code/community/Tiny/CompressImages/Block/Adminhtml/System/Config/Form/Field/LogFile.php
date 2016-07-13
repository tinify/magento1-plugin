<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_LogFile
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Template file used
     *
     * @var string
     */
    protected $_template = 'Tiny/CompressImages/system/config/form/field/log_file.phtml';

    /**
     * Render template
     *
     * @param Varien_Data_Form_Element_Abstract $element
     *
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        return $this->toHtml();
    }

    /**
     * Get the donwload URL.
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/CompressImagesAdminhtml_config/downloadLogs');
    }

    protected function _toHtml()
    {
        if (!Mage::helper('tiny_compressimages')->getLogFileExists()) {
            return '';
        }

        return parent::_toHtml();
    }
}
