<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form extends Mage_Adminhtml_Block_System_Config_Form
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
            ->_addSavedIndicator()
            ->_addApiIndicator()
            ->_addAccountType()
            ->_addLogButtonType()
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
        $this->_elementTypes['compressimages_radios'] = Mage::getConfig()
            ->getBlockClassName('tiny_compressimages/adminhtml_system_config_form_field_radios');

        return $this;
    }

    /**
     * Add a field that shows the status indicator.
     *
     * @return $this
     */
    protected function _addStatusIndicator()
    {
        $this->_elementTypes['compressimages_status'] = Mage::getConfig()
            ->getBlockClassName('tiny_compressimages/adminhtml_system_config_form_field_status');

        return $this;
    }

    /**
     * Add a field that shows how much has been saved.
     *
     * @return $this
     */
    protected function _addSavedIndicator()
    {
        $this->_elementTypes['compressimages_saved'] = Mage::getConfig()
            ->getBlockClassName('tiny_compressimages/adminhtml_system_config_form_field_saved');

        return $this;
    }

    /**
     * Add a field that shows the api status indicator.
     *
     * @return $this
     */
    protected function _addApiIndicator()
    {
        $this->_elementTypes['compressimages_api'] = Mage::getConfig()
            ->getBlockClassName('tiny_compressimages/adminhtml_system_config_form_field_api');

        return $this;
    }

    /**
     * Add a field that shows the api status indicator.
     *
     * @return $this
     */
    protected function _addAccountType()
    {
        $this->_elementTypes['compressimages_account_type'] = Mage::getConfig()
            ->getBlockClassName('tiny_compressimages/adminhtml_system_config_form_field_accountType');

        return $this;
    }

    /**
     * Add a field that shows the api status indicator.
     *
     * @return $this
     */
    protected function _addLogButtonType()
    {
        $this->_elementTypes['compressimages_log_button'] = Mage::getConfig()
            ->getBlockClassName('tiny_compressimages/adminhtml_system_config_form_field_logFile');

        return $this;
    }
}
