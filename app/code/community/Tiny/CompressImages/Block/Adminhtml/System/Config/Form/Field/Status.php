<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2016 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 */
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
