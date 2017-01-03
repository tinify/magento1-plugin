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

        $this->_helper = Mage::helper('tiny_compressimages');
    }

    /**
     * Generate the status for the CompressImages extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        /** @var Tiny_CompressImages_Helper_Config $configHelper */
        $configHelper = Mage::helper('tiny_compressimages/config');

        if (!$configHelper->isConfigured()) {
            if (!$configHelper->getApiKey()) {
                return '<span class="compressimages-api-deactivated">'
                . $this->_helper->__('Please enter your api key to check the compression count.')
                . '</span>';
            }

            if (!$configHelper->isEnabled()) {
                return '<span class="compressimages-api-deactivated">'
                    . $this->_helper->__('Please enable the extension to check the compression count.')
                    . '</span>';
            }
        }

        $compressionCount = Mage::helper('tiny_compressimages/tinify')->compressionCount();

        if ($configHelper->getApiKey() == '') {
            return $this->_helper->__('Add your TinyPNG API key to check the status');
        }

        if ($compressionCount == 500) {
            $button  = '<a href="https://tinypng.com/developers/subscription" target="_blank" id="tinypng_check_status" class="tiny-compressimages-button-orange scalable">';
            $button .= '<span><span><span>Upgrade</span></span></span></a>';

            $onhold = $this->_helper->__('Compression on hold. 500 free images compressed this month.') . ' ';
            $upgrade = $this->_helper->__('Upgrade your account to compress more images');

            return '<span class="compressimages-api-deactivated">' . $onhold . '</span>' . $upgrade . '<br>' . $button;
        }

        return $this->_helper->__(
            'There are %s compressions done this month.',
            $compressionCount
        );
    }
}
