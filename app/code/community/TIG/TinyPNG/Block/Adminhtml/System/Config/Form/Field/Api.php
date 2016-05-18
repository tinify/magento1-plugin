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
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2016 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_TinyPNG_Block_Adminhtml_System_Config_Form_Field_Api extends Varien_Data_Form_Element_Abstract
{
    /**
     * Generate the status for the TinyPNG extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $isConfigured = TIG_TinyPNG_Helper_Config::isConfigured();
        $apiKey = TIG_TinyPNG_Helper_Config::getApiKey();
        $isValidated = Mage::helper('tig_tinypng/tinify')->validate($apiKey);

        if ($isConfigured && $isValidated) {
            $message = '<span class="tinypng_status_success">'
                . Mage::helper('tig_tinypng')->__('Operational')
                . '</span>';
        } else {
            $message = '<span class="tinypng_status_failure">'
                . Mage::helper('tig_tinypng')->__('Nonoperational')
                . '</span>';
        }

        return $message;
    }

    /**
     * @return string
     */
    public function getScopeLabel()
    {
        $_helper = Mage::helper('tig_tinypng');

        $label = parent::getScopeLabel();
        $label .= '<a class="manual-links" href="#" title="' . $_helper->__('Check status') . '">'
            . $_helper->__('Check status')
            . '</a>';

        return $label;
    }
}