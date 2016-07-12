<?php
class Tiny_CompressImages_Model_Resource_Totals extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table and id field name.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tig_tinypng/totals', 'entity_id');
    }
}
