<?php
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