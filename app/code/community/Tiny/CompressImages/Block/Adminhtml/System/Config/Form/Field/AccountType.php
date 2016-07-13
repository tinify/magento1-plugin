<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_AccountType extends Varien_Data_Form_Element_Abstract
{
    /**
     * Generate the status for the CompressImages extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $button = '<a href="https://tinypng.com/developers/subscription" target="_blank" class="manual-links">';
            $button .= Mage::helper('tiny_compressimages')->__('Credits');
        $button .= '</a>';

        return Mage::helper('tiny_compressimages')->__('Free (A maximum of 500 images per month)<br>' . $button);
    }
}
