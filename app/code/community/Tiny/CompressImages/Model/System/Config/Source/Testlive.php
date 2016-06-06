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
class Tiny_CompressImages_Model_System_Config_Source_Testlive
{
    /**
     * Source model for test / live setting.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tig_tinypng');

        /**
         * Used 0, 1 and 2 as values so that Mage::getStoreConfigFlag() would still function for checking if the
         * extension is active. You still need to check if the value is 2 to see if it's in live or test mode.
         */
        $array = array(
            array(
                'value' => '0',
                'label' => $helper->__('Off')
            ),
            array(
                'value' => '1',
                'label' => $helper->__('Test')
            ),
            array(
                'value' => '2',
                'label' => $helper->__('Live')
            ),
        );
        return $array;
    }
}
