<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Radios extends Varien_Data_Form_Element_Radios
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
