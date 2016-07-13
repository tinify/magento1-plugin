<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_LogStatus extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Template file used
     *
     * @var string
     */
    protected $_template = 'Tiny/CompressImages/system/config/form/field/log_status.phtml';

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
     * @return string
     */
    public function getCleanImagesUrl()
    {
        $url = Mage::helper("adminhtml")->getUrl('adminhtml/CompressImagesAdminhtml_config/clearCache');

        return $url;
    }
}
