<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Api extends Varien_Data_Form_Element_Abstract
{
    /**
     * Generate the status for the CompressImages extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $_helper = Mage::helper('tiny_compressimages');
        $apiStatusCache = Mage::app()->loadCache('tiny_compressimages_api_status');
        $message = $_helper->__('Click the button to check the API status.');

        if ($apiStatusCache !== false) {
            $apiStatusCacheData = json_decode($apiStatusCache, true);

            switch ($apiStatusCacheData['status']) {
                case 'operational':
                    $message = '<span class="compressimages_status_success"><span class="apisuccess"></span>'
                        . Mage::helper('tiny_compressimages')->__('API connection successful')
                        . '</span>';
                    break;
                case 'nonoperational':
                    $message = '<span class="compressimages_status_failure">'
                        . Mage::helper('tiny_compressimages')->__('Non-operational')
                        . '</span>';
                    break;
            }
        }

        $message = '<span id="compressimages_api_status">' . $message . '</span><br>';

        return $message;
    }

    /**
     * @return string
     */
    public function getScopeLabel()
    {
        $js = '<script type="text/javascript">
                    var url = "' . Mage::helper("adminhtml")->getUrl('adminhtml/CompressImagesAdminhtml_status/getApiStatus') . '";

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

        $label = parent::getScopeLabel() . $js;

        return $label;
    }
}
