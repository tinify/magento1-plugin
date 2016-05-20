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
    /**
     * @var bool $allowCompression
     */
    public $allowCompression = false;

    /**
     * @var string $destinationSubdir
     */
    public $destinationSubdir = '';

    /**
     * @var SplFileInfo $newFile
     */
    public $newFile = '';

    /**
     * @var string $imageWidth
     */
    public $imageWidth = '';

    /**
     * @var string $imageHeight
     */
    public $imageHeight = '';

    /**
     * @var string $logMessage
     */
    public $logMessage;

    /**
     * @var string $hashBefore
     */
    protected $hashBefore = '';

    /**
     * @var string $hashAfter
     */
    protected $hashAfter = '';

    /**
     * @var int $bytesBefore
     */
    protected $bytesBefore = 0;

    /**
     * @var int $bytesAfter
     */
    protected $bytesAfter = 0;

    /**
     * @var TIG_TinyPNG_Helper_Data $helper
     */
    public $helper;

    /**
     * @var int $storeId
     */
    public $storeId = 0;

    /**
     * Constructor
     */
    public function __construct() {
        require_once(Mage::getBaseDir('lib') . '/tinify-php/lib/Tinify.php');

        spl_autoload_register( array($this, 'load'), true, true );

        $this->helper = Mage::helper('tig_tinypng');

        $version = Mage::getVersion();
        $edition = Mage::getEdition();

        Tinify\setAppIdentifier('Magento ' . $edition . ' ' . $version);
    }

    /**
     * This function autoloads Tinify classes
     *
     * @param string $class
     */
    private static function load($class)
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
            $this->helper->log($e->getMessage(), null, $this->storeId);
            return false;
        }

        return true;
    }

    /**
     * Compress the image.
     *
     * @return bool
     */
    public function compress()
    {
        if (!TIG_TinyPNG_Helper_Config::isEnabled($this->storeId)) {
            $this->helper->log('The TinyPNG module is disabled, not compressing ' . $this->newFile->getPathname(), 'info', $this->storeId);

            return false;
        }

        if (!$this->allowCompression) {
            $this->helper->log('Product imagetype not allowed for compression', 'error', $this->storeId);

            return false;
        }

        /**
         * Check if this file is compressed before. If that is the case, copy it to this location.
         */
        if ($this->_isAlreadyCompressed()) {
            return true;
        }

        try {
            $this->logMessage = '';
            $input = \Tinify\fromFile($this->newFile->getPathname());

            /**
             * If test mode is enabled we compress the image, but will not save the result.
             */
            if (TIG_TinyPNG_Helper_Config::isTestMode($this->storeId)) {
                $this->logMessage .= 'TESTMODE - ';

                if (!is_writable($this->newFile->getPathname())) {
                    throw new TIG_TinyPNG_Exception('The file ' . $this->newFile->getPathname() . ' is not writable!');
                }
            } else {
                $input->toFile($this->newFile->getPathname());
            }

            $this->logMessage .=
                'Compressed: ' . $this->newFile->getFilename() . ' - ' .
                'Variant: '. $this->destinationSubdir . ' - ' .
                'Size (WxH): ' . $this->imageWidth . 'x' . $this->imageHeight . ' - ' .
                'Bytes saved: ' . ($this->bytesBefore - $this->_getFileSize($this->newFile)) . ' - ' .
                'Path: ' . $this->newFile->getPath();

            $this->helper->log($this->logMessage, 'info', $this->storeId);
        } catch (\Tinify\Exception $e) {
            $this->helper->log($e, null, $this->storeId);
            return false;
        }

        if (!TIG_TinyPNG_Helper_Config::isTestMode($this->storeId)) {
            $this->saveCompression();
        }

        return true;
    }

    /**
     * Calculates the hash and checks if the file was processed before. If so, it will copy the base file if it still
     * exists. Otherwise it will delete the record from the database.
     *
     * @return bool
     */
    protected function _isAlreadyCompressed()
    {
        $hash = $this->_getFileHash($this->newFile);

        /** @var TIG_TinyPNG_Model_Image|null $model */
        $model = Mage::getModel('tig_tinypng/image')->getByHash($hash);

        if ($model !== null) {
            return $this->_copyExistingFile($model);
        } else {
            $this->helper->log('No existing file found with hash ' . $hash, 'info', $this->storeId);

            return false;
        }
    }

    /**
     * Check if the file exists. If so, copy it to the new location to prevent duplicate compressions. If does not
     * exists anymore, delete the model.
     *
     * @param TIG_TinyPNG_Model_Image $model
     *
     * @return bool
     */
    protected function _copyExistingFile($model)
    {
        $sourceFile = new SplFileInfo(Mage::getBaseDir() . $model->getPath());

        if (!$sourceFile->isFile()) {
            $message = 'Failed: Copying the source file ' . $sourceFile->getPathname() . ' The file does not exists ' .
                'anymore. Deleting the model (ID: ' . $model->getId() . ').';
            $this->helper->log($message, 'info', $this->storeId);

            $model->delete();

            return false;
        } else {
            $model->addUsedAsSource()->save();

            $this->helper->log('Copying the source file from ' . $sourceFile->getPathname() .
                ' to ' . $this->newFile->getPathname(), 'info', $this->storeId);

            if (TIG_TinyPNG_Helper_Config::isTestMode($this->store)) {
                $this->helper->log('Testmode is enabled, no image is copied');

                return true;
            }

            return copy($sourceFile->getPathname(), $this->newFile->getPathname());
        }
    }

    /**
     * @param Mage_Catalog_Model_Product_Image $image
     * @param $store
     *
     * @return $this
     */
    public function setProductImageCompressData($image, $store = null)
    {
        $this->newFile = new SplFileInfo($image->getNewFile());

        if (!TIG_TinyPNG_Helper_Config::isEnabled($this->storeId)) {
            return $this;
        }

        if (null !== $store) {
            $this->storeId = $store;
        }

        $this->allowCompression  = $this->isCompressionAllowed($image->getDestinationSubdir());
        $this->destinationSubdir = $image->getDestinationSubdir();
        $this->imageWidth        = $image->getWidth();
        $this->imageHeight       = $image->getHeight();

        if (!$this->newFile->isFile()) {
            $this->helper->log('Could not load the core image data', 'info', $this->storeId);

            return $this;
        }

        $this->hashBefore = $this->_getFileHash($this->newFile);
        $this->bytesBefore = $this->_getFileSize($this->newFile);

        return $this;
    }

    /**
     * Save the file meta info to the database. This way we can prevent duplicate compressions.
     *
     * @return $this
     */
    public function saveCompression()
    {
        $this->hashAfter = $this->_getFileHash($this->newFile);
        $this->bytesAfter = $this->_getFileSize($this->newFile);
        $path = str_replace(Mage::getBaseDir(), '', $this->newFile->getPathname());

        /** @var TIG_TinyPNG_Model_Image $tinyPNGModel */
        $model = Mage::getModel('tig_tinypng/image');
        $model->setPath($path);
        $model->setHashBefore($this->hashBefore);
        $model->setHashAfter($this->hashAfter);
        $model->setBytesBefore($this->bytesBefore);
        $model->setBytesAfter($this->bytesAfter);
        $model->setProcessedAt(Varien_Date::now());
        $model->setUsedAsSource(1);
        $model->save();

        return $this;
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

    /**
     * Get all the compressions
     *
     * @return TIG_TinyPNG_Model_Resource_Image_Collection
     */
    public function getCompressionStatus()
    {
        $collection = Mage::getModel('tig_tinypng/image')->getCollection();

        $fromDate = Mage::getModel('core/date')->date('Y-m-01');
        $toDate   = Mage::getModel('core/date')->date('Y-m-t');

        $collection->getSelect()->where('processed_at', array(
            'from' => $fromDate,
            'to'   => $toDate,
            'date' => true
            )
        );

        return $collection;
    }

    /**
     * Generic function so we can change the hash function easily.
     *
     * @param SplFileInfo $file
     *
     * @return string
     */
    protected function _getFileHash(SplFileInfo $file)
    {
        if (!$file->isFile()) {
            return false;
        }

        return md5_file($file->getPathname());
    }

    /**
     * Get the filesize for the specified file.
     *
     * @param SplFileInfo $file
     *
     * @return bool|int
     */
    protected function _getFileSize(SplFileInfo $file)
    {
        if (!$file->isFile())
        {
            return false;
        }

        return $file->getSize();
    }
}