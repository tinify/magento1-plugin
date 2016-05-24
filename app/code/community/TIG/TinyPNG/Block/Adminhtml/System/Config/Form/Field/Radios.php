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
class TIG_TinyPNG_Block_Adminhtml_System_Config_Form_Field_Radios extends Varien_Data_Form_Element_Radios
{
    /**
     * Get the html for each individual radio button.
     *
     * @param array|Varien_Object $option
     * @param string              $selected
     *
     * @return string
     */
    protected function _optionToHtml($option, $selected)
    {
        $html = '<div class="wrapper-radio">';
        $html .= '<input type="radio"'.$this->serialize(array('name', 'class', 'style', 'disabled'));
        if (is_array($option)) {
            $html.= 'value="'
                . $this->_escape($option['value'])
                . '"  id="'
                . $this->getHtmlId()
                . $option['value']
                . '"';

            if ($option['value'] == $selected) {
                $html .= ' checked="checked"';
            }

            $html .= ' />';

            $html .= '<label class="inline" for="'
                . $this->getHtmlId()
                . $option['value']
                . '">'
                . $option['label']
                . '</label>';
        } else if ($option instanceof Varien_Object) {
            $html .= 'id="'
                . $this->getHtmlId()
                . $option->getValue()
                . '"'
                . $option->serialize(
                    array(
                        'label',
                        'title',
                        'value',
                        'class',
                        'style',
                    )
                );

            if (in_array($option->getValue(), $selected)) {
                $html .= ' checked="checked"';
            }

            $html .= ' />';

            $html .= '<label class="inline" for="'
                . $this->getHtmlId()
                . $option->getValue()
                . '">'
                . $option->getLabel()
                . '</label>';
        }

        $html.= '</div>';

        return $html;
    }

    /**
     * Wrap the output in a container div.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = parent::getElementHtml();

        return '<div class="radio-container">' . $html . '</div>';
    }
}