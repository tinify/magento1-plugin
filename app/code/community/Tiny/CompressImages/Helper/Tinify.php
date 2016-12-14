<?php
class Tiny_CompressImages_Helper_Tinify extends Mage_Core_Helper_Abstract
{
    const TINY_COMPRESSIMAGES_MEDIA_DIRECTORY = '/media/catalog/product/optimized';

    const CACHE_KEY = 'tiny_compressimages_api_status';

    /**
     * @var bool $allowCompression
     */
    protected $allowCompression;

    /**
     * @var bool $isProductTypeAllowed
     */
    protected $isProductTypeAllowed = true;

    /**
     * @var string $destinationSubdir
     */
    protected $destinationSubdir = '';

    /**
     * @var SplFileInfo $newFile
     */
    protected $newFile = '';

    /**
     * @var string $imageWidth
     */
    protected $imageWidth = '';

    /**
     * @var string $imageHeight
     */
    protected $imageHeight = '';

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
     * @var null|int $parentId
     */
    protected $parentId = null;

    /**
     * @var bool $isCompressedBefore
     */
    protected $isCompressedBefore = false;

    /**
     * @var int $isUsedAsSource
     */
    protected $isUsedAsSource = 1;

    /**
     * @var Tiny_CompressImages_Helper_Data
     */
    protected $helper;

    /**
     * @var int $storeId
     */
    protected $storeId = 0;

    /**
     * @var Tiny_CompressImages_Helper_Config
     */
    protected $configHelper;

    /**
     * @var null|Tiny_CompressImages_Model_Image
     */
    protected $_model = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->_registerAutoloader();
        $this->_setIdentifier();

        $this->helper = Mage::helper('tiny_compressimages');
        $this->configHelper = Mage::helper('tiny_compressimages/config');

        $apiKey = $this->configHelper->getApiKey($this->storeId);
        $this->allowCompression = $this->validate($apiKey);
    }

    /**
     * Register our custom autoloader, this is needed because Magento can't handle PHP's namespaces.
     *
     * @return $this
     */
    protected function _registerAutoloader()
    {
        require_once(Mage::getBaseDir('lib') . '/TinyCompress/lib/Tinify.php');

        spl_autoload_register( array($this, 'load'), true, true );

        return $this;
    }

    /**
     * Set the app identifier
     *
     * @return $this
     */
    protected function _setIdentifier()
    {
        $version = Mage::getVersion();
        $edition = Mage::getEdition();

        Tinify\setAppIdentifier('Magento ' . $edition . ' ' . $version);

        return $this;
    }

    /**
     * This function autoloads Tinify classes
     *
     * @param string $class
     */
    protected static function load($class)
    {
        /**
         * Project-specific namespace prefix
         */
        $prefix = 'Tinify';

        /**
         * Base directory for the namespace prefix
         */
        $base_dir = Mage::getBaseDir('lib') . '/TinyCompress/lib/';

        if (strpos($class, $prefix) !== 0) {
            /**
             * No, move to the next registered autoloader
             */
            return;
        }

        /**
         * Get the relative class name
         */
        $class_directory = str_replace('\\', '/', $class);

        /**
         * Tinify has all its exceptions in one file, so take that in account
         */
        if (substr($class_directory, -9) == 'Exception') {
            $class_array = explode('/', $class_directory);
            $class_array[count($class_array) - 1] = 'Exception';

            $class_directory = implode('/', $class_array);
        }

        /**
         * Replace the namespace prefix with the base directory, replace namespace
         * separators with directory separators in the relative class name, append
         * with .php
         */
        $file = $base_dir . $class_directory . '.php';

        /**
         * if the file exists, require it
         */
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
        if (empty($apiKey)) {
            return false;
        }

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
     * @throws Tiny_CompressImages_Exception
     */
    public function compress()
    {
        if (!$this->configHelper->isEnabled($this->storeId)) {
            $this->helper->log('The TinyPNG module is disabled, not compressing ' . $this->newFile->getPathname(), 'info', $this->storeId);

            return false;
        }

        if (!$this->_isCompressionAllowed()) {
            $this->helper->log('Compression is not allowed at this moment.', 'info', $this->storeId);

            return false;
        }

        $this->_prepareCompression();

        if ($this->_isInOptimizedMediaDirectory()) {
            $this->helper->log(
                $this->newFile->getPathname(). ' is propably compressed before and can be found in the compression folder'
            );
            $this
                ->_SetCompressionAsPreviously()
                ->_saveCompression();
            return true;
        }

        /**
         * Check if this file is compressed before. If that is the case, copy it to this location.
         */
        if ($this->_isAlreadyCompressed()) {
            return true;
        }

        try {
            $message = '';
            $input = \Tinify\fromFile($this->newFile->getPathname());

            if ($this->configHelper->isTestMode($this->storeId)) {
                $message .= 'TESTMODE - ';

                if (function_exists('mb_strlen')) {
                    $this->bytesAfter = mb_strlen($input->toBuffer(), '8bit');
                } else {
                    $this->bytesAfter = strlen($input->toBuffer());
                }

                if (!is_writable($this->newFile->getPathname())) {
                    throw new Tiny_CompressImages_Exception('The file ' . $this->newFile->getPathname() . ' is not writable!');
                }
            } else {
                $input->toFile($this->newFile->getPathname());
                $this->bytesAfter = $this->_getFileSize($this->newFile);
            }

            $this->_model->createPath();

            $compressionFile = $this->_model->getFilepathOptimized();
            $this->helper->log('Write to compression Folder : '. $compressionFile, 'info', $this->storeId);
            file_put_contents($compressionFile, $input->toBuffer());

            $message .=
                'Compressed: ' . $this->newFile->getFilename() . ' - ' .
                'Variant: '. $this->destinationSubdir . ' - ' .
                'Size (WxH): ' . $this->imageWidth . 'x' . $this->imageHeight . ' - ' .
                'Bytes saved: ' . ($this->bytesBefore - $this->bytesAfter) . ' - ' .
                'Compressed Before'. $this->isCompressedBefore . ' - '.
                'Path: ' . $compressionFile;

            $this->helper->log($message, 'info', $this->storeId);
        } catch (\Tinify\AccountException $e) {
            $this->helper->log($e->getMessage(), 'error', $this->storeId);
        } catch (\Tinify\Exception $e) {
            $this->helper->log($e, null, $this->storeId);
            return false;
        }

        $this->_saveCompression();

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

        /** @var Tiny_CompressImages_Model_Image|null $model */
        $model = Mage::getModel('tiny_compressimages/image')->getByHash($hash);

        if ($model !== null) {
            return $this->_copyExistingFile($model);
        } else {
            $this->helper->log('No existing file found with hash ' . $hash, 'info', $this->storeId);

            return false;
        }
    }

    /**
     * Check if the Optimized media direcotory already contains the images.
     *
     * @return bool
     */
    protected function _isInOptimizedMediaDirectory()
    {
        $path = $this->_model->getPathOptimized();
        $file = new SplFileInfo(Mage::getBaseDir() . $path);

        if ($file->isFile()) {
            $this->isCompressedBefore = true;
            return true;
        }

        return false;
    }

    /**
     * Check if the file exists. If so, copy it to the new location to prevent duplicate compressions. If does not
     * exists anymore, delete the model.
     *
     * @param Tiny_CompressImages_Model_Image $model
     *
     * @return bool
     */
    protected function _copyExistingFile($model)
    {
        $sourceFile = new SplFileInfo(Mage::getBaseDir() . $model->getPathOptimized());

        if (!$sourceFile->isFile()) {
            $message = 'Failed: Copying the source file ' . $sourceFile->getPathname() . '. The file does not exists ' .
                'anymore. Deleting the model (ID: ' . $model->getId() . ').';
            $this->helper->log($message, 'info', $this->storeId);

            $model->delete();

            return false;
        } else {
            $model->addUsedAsSource()->save();

            $this->helper->log('Copying the source file from ' . $sourceFile->getPathname() .
                ' to ' . $this->newFile->getPathname(), 'info', $this->storeId);

            if ($this->configHelper->isTestMode($this->storeId)) {
                $this->helper->log('Testmode is enabled, no image is copied');

                return true;
            }

            $this->hashBefore         = $this->_getFileHash($this->newFile);
            $this->hashAfter          = $model->getHashAfter();
            $this->bytesAfter         = $this->_getFileSize($this->newFile);
            $this->bytesBefore        = $model->getBytesBefore();
            $this->isUsedAsSource     = $model->getUsedAsSource();
            $this->parentId           = $model->getId();
            $this->isCompressedBefore = false;

            $this->_saveCompression();

            // Reset parent id and used at source.
            $this->parentId       = null;
            $this->isUsedAsSource = 1;

            return copy($sourceFile->getPathname(), $this->newFile->getPathname());
        }
    }

    /**
     * @param Mage_Catalog_Model_Product_Image $image
     * @param $storeId
     *
     * @return $this
     */
    public function setProductImage($image, $storeId = null)
    {
        $this->newFile = new SplFileInfo($image->getNewFile());
        $this->hashBefore = $this->_getFileHash($this->newFile);
        $this->bytesBefore = $this->_getFileSize($this->newFile);

        if ($storeId !== null) {
            $this->storeId = $storeId;
        }

        if (!$this->configHelper->isEnabled($this->storeId)) {
            return $this;
        }

        if (!$this->_isProductImageAllowed($image->getDestinationSubdir())) {
            $this->isProductTypeAllowed = false;
            $this->helper->log(
                'Product ' . $image->getDestinationSubdir() . ' image type is not selected for optimization',
                'error',
                $this->storeId
            );

            return $this;
        }

        $this->destinationSubdir    = $image->getDestinationSubdir();
        $this->imageWidth           = $image->getWidth();
        $this->imageHeight          = $image->getHeight();

        if (!$this->newFile->isFile()) {
            $this->helper->log('Could not load the core image data', 'info', $this->storeId);

            return $this;
        }

        return $this;
    }

    /**
     * Prepare the compression. This way the optimized path can be calculated.
     *
     * @return $this
     */
    protected function _prepareCompression()
    {
        $path = str_replace(Mage::getBaseDir(), '', $this->newFile->getPathname());

        /** @var Tiny_CompressImages_Model_Image */
        $this->_model = Mage::getModel('tiny_compressimages/image');
        $this->_model->setPath($path);
        $this->_model->setImageType($this->destinationSubdir);
        $this->_model->setHashBefore($this->hashBefore);
        $this->_model->setBytesBefore($this->bytesBefore);

        return $this;
    }

    /**
     * Save the file meta info to the database. This way we can prevent duplicate compressions.
     *
     * @return $this
     */
    protected function _saveCompression()
    {
        $this->hashAfter = $this->_getFileHash($this->newFile);

        $this->_model->setHashAfter($this->hashAfter);
        $this->_model->setBytesAfter($this->bytesAfter);
        $this->_model->setProcessedAt(Varien_Date::now());
        $this->_model->setUsedAsSource($this->isUsedAsSource);

        if ($this->configHelper->isTestMode($this->storeId)) {
            $this->_model->setIsTest(1);
        }

        $this->_model->setParentId($this->parentId);
        $this->_model->setCompressedBefore($this->isCompressedBefore);

        $this->_model->save();

        $this->setTotalSavings();

        return $this;
    }

    /**
     * @return $this
     */
    protected function _SetCompressionAsPreviously()
    {
        $compressedFile = new SplFileInfo($this->_getOptimizedMediaPath($this->newFile->getPathname()));

        $this->isCompressedBefore = true;

        $this->bytesAfter = $this->_getFileSize($compressedFile);
        $this->hashAfter  = $this->_getFileHash($compressedFile);

        return $this;
    }

    /**
     * Save the file meta for the total month savings.
     *
     * @return $this
     */
    public function setTotalSavings()
    {
        $model      = Mage::getModel('tiny_compressimages/totals');
        $collection = $model->getCollection()->setOrder('entity_id', 'DESC');

        /** @var Tiny_CompressImages_Model_Totals $latest */
        $latest = $collection->getFirstItem();

        $bytesBefore      = $this->bytesBefore;
        $bytesAfter       = $this->bytesAfter;
        $totalCompression = 1;

        if ($this->isBetweenDates($latest->getDateFrom(),$latest->getDateTo())) {
            $bytesBefore      = $bytesBefore + $latest->getTotalBytesBefore();
            $bytesAfter       = $bytesAfter  + $latest->getTotalBytesAfter();
            $totalCompression = $totalCompression + $latest->getTotalCompressions();
            $dateFrom         = $latest->getDateFrom();
            $dateTo           = $latest->getDateTo();

            // Load the latest record for updates.
            $model->load($latest->getEntityId());

        } else {
            $dateFrom = Mage::getModel('core/date')->date('Y-m-01');
            $dateTo   = Mage::getModel('core/date')->date('Y-m-t');
        }

        $model->setTotalBytesBefore($bytesBefore);
        $model->setTotalBytesAfter($bytesAfter);
        $model->setTotalCompressions($totalCompression);
        $model->setDateFrom($dateFrom);
        $model->setDateTo($dateTo);
        $model->setUpdatedAt(Varien_Date::now());

        $model->save();

        return $this;
    }

    /**
     * @param $from
     * @param $to
     *
     * @return bool
     */
    public function isBetweenDates($from, $to)
    {
        $between = false;

        if ((Varien_Date::now() > $from)
            && (Varien_Date::now() < $to)
        ) {
            $between = true;
        }

        return $between;
    }

    /**
     * Check if we are allowed to compress images.
     *
     * @return bool
     */
    protected function _isCompressionAllowed()
    {
        if (!$this->allowCompression) {
            return false;
        }

        if (!$this->isProductTypeAllowed) {
            return false;
        }

        return true;
    }

    /**
     * @param $imageDestination
     *
     * @return bool
     */
    protected function _isProductImageAllowed($imageDestination)
    {
        if (!$this->allowCompression) {
            return false;
        }

        $typesAllowed = $this->configHelper->getProductImageTypesToCompress($this->storeId);

        if (in_array($imageDestination, $typesAllowed)) {
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
        if(!$this->configHelper->isConfigured($store)) {
            return 0;
        }

        $apiKey = $this->configHelper->getApiKey($store);
        $validated = $this->validate($apiKey);

        if (!$validated) {
            return 0;
        }

        return \Tinify\compressionCount();
    }

    /**
     * Get all the compressions
     *
     * @return Tiny_CompressImages_Model_Resource_Image_Collection
     */
    public function getCompressionStatus()
    {
        $collection = Mage::getModel('tiny_compressimages/image')->getCollection();
        $select = $collection->getSelect();
        $select->limit(50);

        $fromDate = Mage::getModel('core/date')->date('Y-m-01');
        $toDate   = Mage::getModel('core/date')->date('Y-m-t');

        $select->where(
            'processed_at',
            array(
                'from' => $fromDate,
                'to'   => $toDate,
                'date' => true
            )
        );

        $collection->setOrder('processed_at', Varien_Data_Collection_Db::SORT_ORDER_DESC);

        return $collection;
    }

    /**
     * Check the API status.
     *
     * @return array
     */
    public function getApiStatus($useCache = false)
    {
        $apiStatusCache = Mage::app()->loadCache(static::CACHE_KEY);
        if ($useCache && $apiStatusCache !== false) {
            return json_decode($apiStatusCache, true);
        }

        /** @var Tiny_CompressImages_Helper_Config $configHelper */
        $configHelper = Mage::helper('tiny_compressimages/config');
        $apiKey = $configHelper->getApiKey();
        $isValidated = Mage::helper('tiny_compressimages/tinify')->validate($apiKey);

        $cacheData = array();
        if (!$apiKey || $isValidated) {
            $message = '<span class="compressimages_status_success">'
                . '<span class="indicator"><img src="' . Mage::getDesign()->getSkinUrl('images/fam_bullet_success.gif') . '"></span>'
                . Mage::helper('tiny_compressimages')->__('API connection successful')
                . '</span>';

            $cacheData['status'] = 'operational';
        } else {
            $message = '<span class="compressimages_status_failure">'
                . '<span class="indicator"><img src="' . Mage::getDesign()->getSkinUrl('images/error_msg_icon.gif') . '"></span>'
                . Mage::helper('tiny_compressimages')->__('Non-operational')
                . '</span>';

            $cacheData['status'] = 'nonoperational';
        }

        $result = array();
        $result['status'] = 'success';
        $result['message'] = $message;

        /**
         * Only cache the results if the api is up.
         */
        if ($cacheData['status'] == 'operational') {
            Mage::app()->saveCache(json_encode($result), static::CACHE_KEY);
        }

        return $result;
    }

    /**
     * @param $file
     *
     * @return string
     */
    protected function _getOptimizedMediaPath($file)
    {
        $baseDir  = Mage::getBaseDir('media');
        $tinyPath = substr(Tiny_CompressImages_Helper_Tinify::TINY_COMPRESSIMAGES_MEDIA_DIRECTORY.DS, 1);

        $path = str_replace(
            $baseDir . DS,
            $tinyPath,
            $file
        );

        return Mage::getBaseDir(). '/' .str_replace(DS, '/', $path);
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

        /**
         * Flush PHP's internal cache so we always have use a fresh copy of the file.
         */
        clearstatcache();

        return $file->getSize();
    }
}
