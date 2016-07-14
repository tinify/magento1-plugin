<?php
class Tiny_CompressImages_CompressImagesAdminhtml_StatusController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @var Tiny_Compressimages_Helper_Tinify
     */
    protected $_helper = null;

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        /** @var Mage_Admin_Model_Session $session */
        $session = Mage::getSingleton('admin/session');

        return $session->isAllowed('admin/compressimages');
    }

    /**
     * Get the Tinify helper.
     *
     * @return Tiny_CompressImages_Helper_Tinify
     */
    public function getHelper()
    {
        if ($this->_helper === null) {
            $this->_helper = Mage::helper('tiny_compressimages/tinify');
        }

        return $this->_helper;
    }

    public function getApiStatusAction()
    {
        if (!$this->_validateFormKey()) {
            return;
        }

        $result = $this->getHelper()->getApiStatus();

        /** @var Mage_Core_Helper_Data $coreHelper */
        $coreHelper = Mage::helper('core');
        $this->getResponse()->setBody($coreHelper->jsonEncode($result));
    }
}
