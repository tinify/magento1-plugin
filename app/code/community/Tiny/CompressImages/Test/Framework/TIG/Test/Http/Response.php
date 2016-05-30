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
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) 2015 Total Internet Group B.V. (http://www.tig.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class Tiny_CompressImages_Test_Framework_TIG_Test_Http_Response extends Mage_Core_Controller_Response_Http
{
    /**
     * @var bool
     */
    protected $_headersSent = false;

    /**
     * @param boolean $headersSent
     *
     * @return TIG_Test_Http_Response
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