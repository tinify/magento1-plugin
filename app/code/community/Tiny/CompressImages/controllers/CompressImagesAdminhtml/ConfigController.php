<?php
class Tiny_CompressImages_CompressImagesAdminhtml_ConfigController extends Mage_Adminhtml_Controller_Action
{
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
     * Download CompressImages log file
     *
     * @return $this
     */
    public function downloadLogsAction()
    {
        /** @var Tiny_CompressImages_Helper_Data $helper */
        $helper = Mage::helper('tiny_compressimages');
        $filePath = $helper->getLogFilePath();

        if (!$helper->getLogFileExists()) {
            return $this;
        }

        $content = array(
            'type'  => 'filename',
            'value' => $filePath,
            'rm'    => false,
        );

        $this->postDispatch();
        $this->_prepareDownloadResponse($helper->getLogFilename(), $content);

        return $this;
    }

    /**
     * @return bool
     */
    public function clearCacheAction()
    {
        try {
            Mage::getModel('catalog/product_image')->clearCache();
            Mage::dispatchEvent('clean_catalog_images_cache_after');
            $this->_getSession()->addSuccess(
                Mage::helper('adminhtml')->__('The image cache was cleaned.')
            );
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('adminhtml')->__('An error occurred while clearing the image cache.')
            );
        }
        $this->_redirect('adminhtml/system_config/edit/section/tiny_compressimages');
    }
}
