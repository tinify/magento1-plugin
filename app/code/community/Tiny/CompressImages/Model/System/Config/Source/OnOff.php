<?php
class Tiny_CompressImages_Model_System_Config_Source_OnOff
{
    /**
     * Source model for on / off selection
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tiny_compressimages');

        $array = array(
            array(
                'value' => 0,
                'label' => $helper->__('Off')
            ),
            array(
                'value' => 1,
                'label' => $helper->__('On')
            ),
        );

        return $array;
    }
}
