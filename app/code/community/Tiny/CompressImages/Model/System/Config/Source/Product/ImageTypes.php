<?php
class Tiny_CompressImages_Model_System_Config_Source_Product_ImageTypes
{
    /**
     * Source product model for collecting the image types.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tiny_compressimages');

        $array = array(
            array(
                'value' => 'thumbnail',
                'label' => $helper->__('Thumbnail')
            ),
            array(
                'value' => 'small_image',
                'label' => $helper->__('Small Image')
            ),
            array(
                'value' => 'image',
                'label' => $helper->__('Base Image')
            ),
            array(
                'value' => 'media_image',
                'label' => $helper->__('Media Image')
            ),
        );

        return $array;
    }
}
