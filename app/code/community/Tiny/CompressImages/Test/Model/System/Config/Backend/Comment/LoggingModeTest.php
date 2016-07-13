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
class Tiny_CompressImages_Test_Model_System_Config_Backend_Comment_LoggingModeTest
    extends Tiny_CompressImages_Test_Framework_Tiny_Test_TestCase
{
    /**
     * @var null|Tiny_CompressImages_Model_System_Config_Backend_Comment_LoggingMode
     */
    protected $_instance = null;

    /**
     * @var null|string
     */
    protected $_logPath = null;

    public function setUp()
    {
        $this->_logPath = Mage::helper('tiny_compressimages')->getLogFilePath();
        $this->_instance = new Tiny_CompressImages_Model_System_Config_Backend_Comment_LoggingMode;

        if (file_exists($this->_logPath)) {
            unlink($this->_logPath);
        }
    }

    public function testCommentReturnsLink()
    {
        $text = $this->_instance->getCommentText();
        $this->assertNotContains('<a href', $text);

        touch($this->_logPath);
        $text = $this->_instance->getCommentText();
        $this->assertContains('<a href', $text);
    }
}