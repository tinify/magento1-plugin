<?php
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