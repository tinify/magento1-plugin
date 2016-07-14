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
class Tiny_CompressImages_Test_Helper_DataTest extends Tiny_CompressImages_Test_Framework_Tiny_Test_TestCase
{
    /**
     * @var Tiny_CompressImages_Helper_Config
     */
    protected $_instance = null;

    public function setUp()
    {
        $this->_instance = Mage::helper('tiny_compressimages/config');
    }

    public function getProductImageTypesToCompressDataProvider()
    {
        return array(
            array(
                array(
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_BASE => 1,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SMALL => 1,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_THUMBNAIL => 1,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SWATCHES => 1,
                ),
                array(
                    'image',
                    'small_image',
                    'thumbnail',
                    'media_image',
                    'swatches',
                ),
            ),
            array(
                array(
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_BASE => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SMALL => 1,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_THUMBNAIL => 1,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SWATCHES => 1,
                ),
                array(
                    'small_image',
                    'thumbnail',
                    'media_image',
                    'swatches',
                ),
            ),
            array(
                array(
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_BASE => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SMALL => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_THUMBNAIL => 1,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SWATCHES => 1,
                ),
                array(
                    'thumbnail',
                    'media_image',
                    'swatches',
                ),
            ),
            array(
                array(
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_BASE => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SMALL => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_THUMBNAIL => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SWATCHES => 1,
                ),
                array(
                    'swatches',
                ),
            ),
            array(
                array(
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_BASE => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SMALL => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_THUMBNAIL => 0,
                    Tiny_CompressImages_Helper_Config::XPATH_IMAGE_TYPE_SWATCHES => 0,
                ),
                array(
                ),
            ),
        );
    }

    /**
     * @dataProvider getProductImageTypesToCompressDataProvider
     */
    public function testGetProductImageTypesToCompress($config, $shouldHave)
    {
        foreach ($config as $path => $value) {
            Mage::app()->getStore()->setConfig($path, $value);
        }

        $result = $this->_instance->getProductImageTypesToCompress();

        $this->assertEquals($shouldHave, $result);
    }
}