<?php
class Tiny_CompressImages_Block_Adminhtml_Cache_Warning extends Mage_Adminhtml_Block_Template
{
    public function getCleanImagesUrl()
    {
        return $this->getUrl('*/*/cleanImages');
    }
}
