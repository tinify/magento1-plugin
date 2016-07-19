<?php
class Tiny_CompressImages_Model_Resource_Image extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table and id field name.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tiny_compressimages/image', 'image_id');
    }
}
