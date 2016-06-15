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
class Tiny_CompressImages_TinypngAdminhtml_StatusController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        /** @var Mage_Admin_Model_Session $session */
        $session = Mage::getSingleton('admin/session');

        return $session->isAllowed('admin/tinypng');
    }

    public function getApiStatusAction()
    {
        if (!$this->_validateFormKey()) {
            return;
        }

        $result = array();

        /** @var Tiny_CompressImages_Helper_Config $configHelper */
        $configHelper = Mage::helper('tig_tinypng/config');
        $isConfigured = $configHelper->isConfigured();
        $apiKey = $configHelper->getApiKey();
        $isValidated = Mage::helper('tig_tinypng/tinify')->validate($apiKey);

        $cacheData = array();
        if ($isConfigured && $isValidated) {
            $message = '<span class="tinypng_status_success"><span class="apisuccess"></span>'
                . Mage::helper('tig_tinypng')->__('API connection successful')
                . '</span>';

            $cacheData['status'] = 'operational';
            Mage::app()->saveCache(json_encode($cacheData), 'tig_tinypng_api_status');
        } else {
            $message = '<span class="tinypng_status_failure">'
                . Mage::helper('tig_tinypng')->__('Non-operational')
                . '</span>';

            $cacheData['status'] = 'nonoperational';
            Mage::app()->saveCache(json_encode($cacheData), 'tig_tinypng_api_status');
        }

        $result['status'] = 'success';
        $result['message'] = $message;

        /** @var Mage_Core_Helper_Data $coreHelper */
        $coreHelper = Mage::helper('core');
        $this->getResponse()->setBody($coreHelper->jsonEncode($result));
    }
}
