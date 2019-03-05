<?php
class Tiny_CompressImages_Test_Model_ObserverTest extends Tiny_CompressImages_Test_Framework_Tiny_Test_TestCase
{
    public function testTheModuleDoesNotTriggerAnythingWhenDisabled()
    {
        $tinifyHelperMock = $this->getMock('Tiny_CompressImages_Helper_Tinify');

        $tinifyHelperMock
            ->expects($this->never())
            ->method('setProductImage');

        $configHelperMock = $this->getMock('Tiny_CompressImages_Helper_Config');

        $configHelperMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $instance = new Tiny_CompressImages_Model_Observer;
        $this->setProperty('_tinifyHelper', $tinifyHelperMock, $instance);
        $this->setProperty('_configHelper', $configHelperMock, $instance);

        $instance->catalogProductImageSaveAfter(null);
    }
}
