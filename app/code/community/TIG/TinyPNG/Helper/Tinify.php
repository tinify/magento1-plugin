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
class TIG_TinyPNG_Helper_Tinify extends Mage_Core_Helper_Abstract
{
    /** @var bool $allowCompression */
    public $allowCompression = false;

    /** @var string $destinationSubdir */
    public $destinationSubdir = '';

    /** @var string $newFile */
    public $newFile = '';

    /** @var string $imageWidth */
    public $imageWidth = '';

    /** @var string $imageHeight */
    public $imageHeight = '';

    /** @var string $logMessage */
    public $logMessage;

    /** @var string $compressHash */
    protected $compressHash = '';

    /** @var int $imageBytes */
    protected $imageBytes = 0;

    /** @var string  $compressHashAfter */
    protected $compressHashAfter = '';

    /** @var int  $imageBytesAfter */
    protected $imageBytesAfter = 0;

    /** @var bool $compression */
    protected $compression = false;

    /** @var TIG_TinyPNG_Helper_Data $helper */
    public $helper;

    /** @var  int $storeId */
    public $storeId = 0;

    /**
     * Constructor
     */
    public function __construct() {
        require_once(Mage::getBaseDir('lib') . '/tinify-php/lib/Tinify.php');

        spl_autoload_register( array($this, 'load'), true, true );

        $this->helper = Mage::helper('tig_tinypng');
    }

    /**
     * This function autoloads Tinify classes
     *
     * @param string $class
     */
    private static function load( $class )
    {
        /** Project-specific namespace prefix */
        $prefix = '';

        /** Base directory for the namespace prefix */
        $base_dir = Mage::getBaseDir('lib') . '/tinify-php/lib/';

        /** Does the class use the namespace prefix? */
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            /** No, move to the next registered autoloader */
            return;
        }

        /** Get the relative class name */
        $relative_class = substr($class, $len);
        $relative_class_directory = str_replace('\\', '/', $relative_class);

        /** Tinify has all its exceptions in one file, so take that in account */
        if (substr($relative_class_directory, -9) == 'Exception') {
            $class_array = explode('/', $relative_class_directory);
            $class_array[count($class_array) - 1] = 'Exception';

            $relative_class_directory = implode('/', $class_array);
        }

        /**
         * Replace the namespace prefix with the base directory, replace namespace
         * separators with directory separators in the relative class name, append
         * with .php
         */
        $file = $base_dir . $relative_class_directory . '.php';

        /** if the file exists, require it */
        if (file_exists($file)) {
            require $file;
        }
    }

    /**
     * Validate the Tinify Api Key.
     *
     * @param $apiKey
     *
     * @return bool
     */
    public function validate($apiKey) {
        \Tinify\setKey($apiKey);
        try {
            \Tinify\validate();
        } catch (\Tinify\Exception $e) {
            $this->helper->logMessage($e->getMessage(), null, $this->storeId);
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function compress() {

        if (!$this->allowCompression) {
            $this->helper->logMessage('Product imagetype not allowed for compression', 'failure', $this->storeId);
            return false;
        }

        try {
            $this->compression = \Tinify\fromFile($this->newFile)->toFile($this->newFile);
            $this->logMessage .= 'Variant '. $this->destinationSubdir .
                'allowed ' . $this->allowCompression .
                'width ' . $this->imageWidth .
                'height ' . $this->imageHeight .
                'API ' . ''. // the $this->ApiKey when placed in contructor
                'JSON Respons : '. json_encode($this->compression);
        } catch (\Tinify\Exception $e) {
            $this->helper->logMessage($e->getCode() .': '. $e->getMessage(), null, $this->storeId);
            return false;
        }

        $this->saveCompression($this->compression);
        return true;

    }

    /**
     * @param Mage_Catalog_Model_Product_Image $image
     * @param $store
     *
     * @return $this
     */
    public function setProductImageCompressData($image, $store = null)
    {
        if (null !== $store) {
            $this->storeId = $store;
        }

        $this->allowCompression  = $this->isCompressionAllowed($image->getDestinationSubdir());
        $this->destinationSubdir = $image->getDestinationSubdir();
        $this->newFile           = $image->getNewFile();
        $this->imageWidth        = $image->getWidth();
        $this->imageHeight       = $image->getHeight();

        // Gets the core data of file for setting the filesize.
        $fileInfo = new SplFileInfo($image->getNewFile());
        if (!$fileInfo->isFile()) {
            $this->helper->logMessage('Could not load the core image data', 'info', $this->storeId);
        } else {
            $this->imageBytes = $fileInfo->getSize();
        }

        $this->compressHash = md5_file($image->getNewFile());

        return $this;
    }

    public function saveCompression()
    {
        /** @todo save compression in DB */
    }

    /**
     * @param $imageDestination
     *
     * @return bool
     */
    public function isCompressionAllowed($imageDestination)
    {
        $typesAllowed = TIG_TinyPNG_Helper_Config::getProductImageTypesToCompress($this->storeId);

        /**
         * @TODO: Should be inside the constructor.
         */
        $apiKey     = TIG_TinyPNG_Helper_Config::getApiKey($this->storeId);
        $validated  = $this->validate($apiKey);

        if (in_array($imageDestination, explode(',', $typesAllowed))
            && $validated
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check how many images have been compressed this month
     *
     * @param null $store
     *
     * @return int|null
     */
    public function compressionCount($store = null) {
        if(!TIG_TinyPNG_Helper_Config::isConfigured($store)) {
            return 0;
        }

        $apiKey = TIG_TinyPNG_Helper_Config::getApiKey($store);
        $validated = $this->validate($apiKey);

        if (!$validated) {
            return 0;
        }

        return \Tinify\compressionCount();
    }
}