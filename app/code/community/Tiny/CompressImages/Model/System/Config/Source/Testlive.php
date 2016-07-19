<?php
class Tiny_CompressImages_Model_System_Config_Source_Testlive
{
    /**
     * Source model for test / live setting.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tiny_compressimages');

        /**
         * Used 0, 1 and 2 as values so that Mage::getStoreConfigFlag() would still function for checking if the
         * extension is active. You still need to check if the value is 2 to see if it's in live or test mode.
         */
        $array = array(
            array(
                'value' => '2',
                'label' => $helper->__('Live')
            ),
            array(
                'value' => '1',
                'label' => $helper->__('Test')
            ),
            array(
                'value' => '0',
                'label' => $helper->__('Disabled')
            ),
        );
        return $array;
    }
}
