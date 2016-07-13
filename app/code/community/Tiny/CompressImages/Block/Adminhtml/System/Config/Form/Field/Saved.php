<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Saved extends Varien_Data_Form_Element_Abstract
{
    /**
     * Generate the status for the CompressImages extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $data = Mage::getModel('tiny_compressimages/totals')->getTotalCompressionInformation();

        return Mage::helper('tiny_compressimages')->__(
            'Saved %s%% over a total of %s compressions',
            $data['percentageSaved'],
            $data['totalCompressions']
        );

    }
}
