<?php
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