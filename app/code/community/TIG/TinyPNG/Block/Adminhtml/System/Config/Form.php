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
class TIG_TinyPNG_Block_Adminhtml_System_Config_Form extends Mage_Adminhtml_Block_System_Config_Form
{
    /**
     * @var array
     */
    protected $_elementTypes = array();

    /**
     * Add the new form elements
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        $this->_elementTypes = parent::_getAdditionalElementTypes();

        $this
            ->_addRadioButtons()
            ->_addStatusIndicator()
            ->_addApiIndicator()
        ;

        return $this->_elementTypes;
    }

    /**
     * Add the Off/Live/Test radio button list.
     *
     * @return $this
     */
    protected function _addRadioButtons()
    {
        $this->_elementTypes['tinypng_radios'] = Mage::getConfig()
            ->getBlockClassName('tig_tinypng/adminhtml_system_config_form_field_radios');

        return $this;
    }

    /**
     * Add a field that shows the status indicator.
     *
     * @return $this
     */
    protected function _addStatusIndicator()
    {
        $this->_elementTypes['tinypng_status'] = Mage::getConfig()
            ->getBlockClassName('tig_tinypng/adminhtml_system_config_form_field_status');

        return $this;
    }

    /**
     * Add a field that shows the api status indicator.
     *
     * @return $this
     */
    protected function _addApiIndicator()
    {
        $this->_elementTypes['tinypng_api'] = Mage::getConfig()
            ->getBlockClassName('tig_tinypng/adminhtml_system_config_form_field_api');

        return $this;
    }
}