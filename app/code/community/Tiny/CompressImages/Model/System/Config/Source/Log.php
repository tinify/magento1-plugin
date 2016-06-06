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
class Tiny_CompressImages_Model_System_Config_Source_Log
{
    /**
     * Get the select list for logging mode.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('tig_tinypng');

        $array = array(
            array(
                'value' => 'off',
                'label' => $helper->__('Logging disabled')
            ),
            array(
                'value' => 'only_exceptions',
                'label' => $helper->__('Exceptions only')
            ),
            array(
                'value' => 'fail_and_exceptions',
                'label' => $helper->__('Errors and Exceptions')
            ),
            array(
                'value' => 'all',
                'label' => $helper->__('All logging information')
            ),
        );

        return $array;
    }
}
