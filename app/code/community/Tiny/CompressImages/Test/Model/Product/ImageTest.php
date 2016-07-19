<?php
class Model_Product_ImageTest extends Tiny_CompressImages_Test_Framework_Tiny_Test_TestCase
{
    /**
     * @var null|Tiny_CompressImages_Helper_Config
     */
    protected $_helper = null;

    /**
     * @var Mage_Catalog_Model_Product_Image
     */
    protected $_instance = null;

    public function setUp()
    {
        $this->_instance = Mage::getModel('catalog/product_image');
        $this->_helper = $this->getMock('Tiny_CompressImages_Helper_Config');

        $this->setProperty('_configHelper', $this->_helper);
    }

    public function testGetUrl()
    {
        $this->_helper
            ->expects($this->once())
            ->method('isTestMode')
            ->willReturn('false');

        $this->setProperty('_newFile', 'test.jpg');

        $url = Mage::getBaseUrl('web');
        $result = $this->_instance->getUrl();

        $this->assertEquals('http://magento.local/media/test.jpg', str_replace($url, 'http://magento.local/', $result));

        Mage::app()->getStore()->setConfig('web/seo/', 1);

        $this->assertEquals('http://magento.local/media/test.jpg', str_replace($url, 'http://magento.local/', $result));
    }
}