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
class Tiny_CompressImages_Test_Block_Adminhtml_System_Config_Form_Field_LogStatusTest
    extends Tiny_CompressImages_Test_Framework_Tiny_Test_TestCase
{
    /**
     * @var Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_LogStatus
     */
    protected $_instance = null;

    protected function setUp()
    {
        $this->_instance = new Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_LogStatus;
        $this->_instance->setArea('adminhtml');

        Mage::getModel('tiny_compressimages/image')->deleteAll();
    }

    public function testImagesLimit()
    {
        $html = $this->_instance->toHtml();
        $this->assertNotContains('id="show_all"', $html);

        for ($i = 0; $i < 11; $i++) {
            $model = Mage::getModel('tiny_compressimages/image');
            $model->setProcessedAt(Varien_Date::now());
            $model->save();
        }

        $html = $this->_instance->toHtml();
        $this->assertContains('id="show_all"', $html);
    }
}