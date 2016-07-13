<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_SupportTab
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Template file used
     *
     * @var string
     */
    protected $_template = 'Tiny/CompressImages/system/config/form/field/support_tab.phtml';

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        return $this->toHtml();
    }

    /**
     * Get the current version of the PostNL extension's code base.
     *
     * @return string
     */
    public function getModuleVersion()
    {
        $version = (string) Mage::getConfig()->getModuleConfig('Tiny_CompressImages')->version;

        return $version;
    }

    /**
     * Get the current stability of the PostNL extension's code base.
     *
     * @return string
     */
    public function getModuleStability()
    {
        $stability = (string) Mage::getConfig()->getModuleConfig('Tiny_CompressImages')->stability;

        return $stability;
    }
}
