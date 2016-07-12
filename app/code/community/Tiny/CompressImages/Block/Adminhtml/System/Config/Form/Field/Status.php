<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Status extends Varien_Data_Form_Element_Abstract
{
    /**
     * @var Tiny_CompressImages_Helper_Data
     */
    protected $_helper = null;

    /**
     * The constructor
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);

        $this->_helper = Mage::helper('tig_tinypng');
    }

    /**
     * Generate the status for the TinyPNG extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        // TODO: Find a method to determine whether to use our or Tinify's compression count
        $compressionCount = Mage::helper('tig_tinypng/tinify')->compressionCount();

        /** @var Tiny_CompressImages_Helper_Config $configHelper */
        $configHelper = Mage::helper('tig_tinypng/config');

        if ($configHelper->getApiKey() == '') {
            return $this->_helper->__('Add your TinyPNG API key to check the status');
        }

        if ($compressionCount == 0 || $compressionCount == 500) {


            $button  = '<a href="https://tinypng.com/developers/subscription" target="_blank" id="tinypng_check_status" class="tig-tinypng-button-orange scalable">';
            $button .= '<span><span><span>Upgrade</span></span></span></a>';


            $onhold = $this->_helper->__('Compression on hold. 500 free images compressed this month.');
            $upgrade = $this->_helper->__('Upgrade your account to compress more images');

            return '<span class="tinypng-api-deactivated">' . $onhold . '</span>' . $upgrade . '<br>' . $button;
        }

        return $this->_helper->__(
            'There are %s compressions done this month.',
            $compressionCount
        );
    }
}
