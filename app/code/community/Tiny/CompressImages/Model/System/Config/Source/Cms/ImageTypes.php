<?php
class Tiny_CompressImages_Model_System_Config_Source_Cms_ImageTypes
{
    /**
     * Source cms model for collecting the image types.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tiny_compressimages');

        $array = array(
            array(
                'value' => 'category_images',
                'label' => $helper->__('Category images')
            ),
            array(
                'value' => 'block_images',
                'label' => $helper->__('Block images')
            ),
            array(
                'value' => 'cms_images',
                'label' => $helper->__('CMS page images')
            ),
        );

        return $array;
    }
}
