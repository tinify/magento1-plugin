<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Credits
    extends Varien_Data_Form_Element_Abstract
{
    const TINY_COMPRESSIMAGES_BASE_UPGRADE_URL = 'https://tinypng.com/dashboard/api?type=upgrade&mail=';

    /**
     * @var Tiny_CompressImages_Helper_Data
     */
    protected $_helper;

    /**
     * @var Tiny_CompressImages_Helper_Tinify
     */
    protected $_tinifyHelper;

    /**
     * @var Tiny_CompressImages_Helper_Config
     */
    protected $_configHelper;

    /**
     * The constructor
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);

        $this->_helper       = Mage::helper('tiny_compressimages');
        $this->_tinifyHelper = Mage::helper('tiny_compressimages/tinify');
        $this->_configHelper = Mage::helper('tiny_compressimages/config');

    }

    /**
     * Generate the amount of credits remaining for the CompressImages extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        if (!$this->_configHelper->isConfigured() && !$this->_configHelper->getApiKey()) {
            return '<span class="compressimages-api-deactivated">'
                . $this->_helper->__('Please enter your api key to check the amount of compressions left.')
                . '</span>';
        }

        if (!$this->_configHelper->isConfigured() && !$this->_configHelper->isEnabled()) {
            return '<span class="compressimages-api-deactivated">'
                . $this->_helper->__('Please enable the extension to check the amount of compressions left.')
                . '</span>';
        }

        return $this->messageGenerator();
    }

    protected function messageGenerator()
    {
        $payingState      = $this->_tinifyHelper->getPayingState();
        $remainingCredits = $this->_tinifyHelper->getRemainingCredits();

        if (!$remainingCredits && $payingState !== 'free') {
            $remainingCredits = 'unlimited';
        }

        $resultString = $this->_helper->__(
            'You are on a <b>%s plan</b> with <b>%s</b> compressions left this month.', $payingState, $remainingCredits
        );

        if ($payingState === 'free') {
            $resultString = $this->addUpgradeButton($resultString);
        }

        return $resultString;
    }

    /**
     * @param $resultString
     *
     * @return string
     */
    protected function addUpgradeButton($resultString)
    {
        $apiEmail   = $this->_tinifyHelper->getApiEmail();
        $upgradeUrl = self::TINY_COMPRESSIMAGES_BASE_UPGRADE_URL . $apiEmail;

        $resultString .= '<p class="tinypng-upgrade-text">'
            . $this->_helper->__('Remove all limitations? Visit your TinyPNG dashboard to upgrade your account.')
            . '</p>'
            . '<a href="' . $upgradeUrl . '" class="tinypng-upgrade-button" target="_blank">'
            . $this->_helper->__('Upgrade account')
            . '</a>';

        return $resultString;
    }
}
