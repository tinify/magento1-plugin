<?php
class Tiny_CompressImages_Test_Framework_Tiny_Test_Http_Response extends Mage_Core_Controller_Response_Http
{
    /**
     * @var bool
     */
    protected $_headersSent = false;

    /**
     * @param boolean $headersSent
     *
     * @return Tiny_CompressImages_Test_Framework_Tiny_Test_Http_Response
     */
    public function setHeadersSent($headersSent)
    {
        $this->_headersSent = $headersSent;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHeadersSent()
    {
        return $this->_headersSent;
    }

    /**
     * @param bool $throw
     *
     * @return bool
     */
    public function canSendHeaders($throw = false)
    {
        $canSendHeaders = !$this->getHeadersSent();
        return $canSendHeaders;
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function sendHeaders()
    {
        $this->setHeadersSent(true);

        return $this;
    }

    /**
     * @return $this
     */
    public function sendResponse()
    {
        return $this;
    }

}
