<?php
class Tiny_CompressImages_Model_System_Config_Source_Log
{
    /**
     * Get the select list for logging mode.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tiny_compressimages');

        $array = array(
            array(
                'value' => 'off',
                'label' => $helper->__('Logging disabled')
            ),
            array(
                'value' => 'only_exceptions',
                'label' => $helper->__('Exceptions only')
            ),
            array(
                'value' => 'fail_and_exceptions',
                'label' => $helper->__('Errors and Exceptions')
            ),
            array(
                'value' => 'all',
                'label' => $helper->__('All logging information')
            ),
        );

        return $array;
    }
}
