<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Api extends Varien_Data_Form_Element_Abstract
{
    /**
     * @var Tiny_Compressimages_Helper_Config
     */
    protected $_configHelper = null;

    /**
     * @var Tiny_Compressimages_Helper_Tinify
     */
    protected $_tinifyHelper = null;

    /**
     * @var string
     */
    protected $output = '';

    /**
     * Get the config helper.
     *
     * @return Tiny_CompressImages_Helper_Config
     */
    public function getConfigHelper()
    {
        if ($this->_configHelper === null) {
            $this->_configHelper = Mage::helper('tiny_compressimages/config');
        }

        return $this->_configHelper;
    }

    /**
     * Get the Tinify helper.
     *
     * @return Tiny_CompressImages_Helper_Tinify
     */
    public function getTinifyHelper()
    {
        if ($this->_tinifyHelper === null) {
            $this->_tinifyHelper = Mage::helper('tiny_compressimages/tinify');
        }

        return $this->_tinifyHelper;
    }

    /**
     * Generate the status for the CompressImages extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $this
            ->_getStatusHtml()
            ->_getJavascript()
        ;

        return $this->output;
    }

    /**
     * Generate the status message.
     *
     * @return $this
     */
    protected function _getStatusHtml()
    {
        $result = $this->getTinifyHelper()->getApiStatus(true);

        $this->output .= $result['message'];

        return $this;
    }

    /**
     * Generate the javascript to make the Ajax call to check the API.
     *
     * @return $this
     */
    protected function _getJavascript()
    {
        $js = '';

        if ($this->getConfigHelper()->isEnabled()) {
            $js
                .= '<script type="text/javascript">
                    var url = "' . Mage::helper("adminhtml")->getUrl(
                    'adminhtml/CompressImagesAdminhtml_status/getApiStatus'
                ) . '";

                    document.observe("dom:loaded", function() {
                        new Ajax.Request(url,
                            {
                                method: "post",
                                onSuccess: function (data) {
                                    var result = data.responseText.evalJSON(true);
                                    if (result.status == "success") {
                                        $("compressimages_api_status").innerHTML = result.message;
                                    }
                                }
                            })
                    });
                </script>';
        }

        $this->output .= $js;

        return $this;
    }
}
