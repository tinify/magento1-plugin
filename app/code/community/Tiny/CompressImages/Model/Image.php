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
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2016 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 */

/**
 * @method $this setPath(String $path);
 * @method string|null getPath();
 * @method $this setImageType(String $type);
 * @method string|null getImageType();
 * @method $this setHashBefore(String $hash);
 * @method string|null getHashBefore();
 * @method $this setHashAfter(String $hash);
 * @method string|null getHashAfter();
 * @method $this setBytesBefore(int $bytes);
 * @method int|null getBytesBefore();
 * @method $this setBytesAfter(int $bytes);
 * @method int|null getBytesAfter();
 * @method $this setUsedAsSource(int $times);
 * @method int|null getUsedAsSource();
 * @method $this setProcessedAt(String $date);
 * @method string|null getProcessedAt();
 * @method $this setIsTest(int $testMode);
 * @method string|null getIsTest();
 * @method $this setCompressedBefore(int $compressedBefore);
 * @method int|null getCompressedBefore();
 * @method $this setParentId(int $parentId);
 * @method int|null getParentId();
 */
class Tiny_CompressImages_Model_Image extends Mage_Core_Model_Abstract
{
    /**
     * @var Tiny_CompressImages_Helper_Data
     */
    protected $_helper = null;

    /**
     * The constructer. Loads the helper.
     */
    public function __construct()
    {
        parent::__construct();

        $this->_helper = Mage::helper('tig_tinypng');
    }

    /**
     * Class constructor.
     */
    public function _construct()
    {
        $this->_init('tig_tinypng/image');
    }

    /**
     * Get the statistics for the TinyPNG module.
     *
     * $options:
     * - current_month: defaults to true
     *
     * @param array $options
     *
     * @return Varien_Object
     */
    public function getStatistics($options = array())
    {
        $collection = $this->getCollection();

        /**
         * Filter by the current month.
         */
        if (
            !isset($options['current_month']) ||
            (
                isset($options['current_month']) &&
                $options['current_month']
            )
        ) {
            $dateFrom = Mage::getModel('core/date')->date('Y-m-01');
            $dateTo   = Mage::getModel('core/date')->date('Y-m-t');

            $collection->addFieldToFilter(
                'processed_at',
                array(
                    'from' => $dateFrom,
                    'to'   => $dateTo,
                    'date' => true
                )
            );
        }

        $collection
            ->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('count(image_id) as images_count')
            ->columns('sum(bytes_before) as bytes_before')
            ->columns('sum(bytes_after) as bytes_after')
            ->columns('max(((bytes_before - bytes_after) / bytes_before) * 100) as greatest_saving')
        ;

        $data = $collection->getFirstItem();

        if ($data->images_count > 0) {
            $data->setData('percentage_saved', (($data->bytes_before - $data->bytes_after) / $data->bytes_before) * 100);
        } else {
            $data->setData('percentage_saved', 0);
        }

        return $data;
    }

    /**
     * Retrieve a model by the hash.
     *
     * @param $hash
     *
     * @return Tiny_CompressImages_Model_Image|null
     */
    public function getByHash($hash)
    {
        /** @var Tiny_CompressImages_Model_Resource_Image_Collection $tinyPNGModel */
        $model = $this->getCollection();
        $model->addFieldToFilter(
            array(
                'hash_before',
                'hash_after',
            ),
            array(
                array('eq' => $hash),
                array('eq' => $hash),
            )
        );

        $item = $model->getFirstItem();
        if ($item->getId() !== null) {
            return $item;
        } else {
            return null;
        }
    }

    /**
     * Simple function to add 1 to the used_as_source column.
     *
     * @return $this
     */
    public function addUsedAsSource()
    {
        $this->setUsedAsSource($this->getUsedAsSource() + 1);

        return $this;
    }

    /**
     * Calculate the url for this image.
     *
     * @return string
     */
    public function getImageUrl()
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $url .= ltrim($this->getPath(), '/media');
        $url = str_replace('media', 'media/image_compression', $url);
        
        return $url;
    }

    /**
     * Calculate the bytes saved.
     *
     * @return null|string
     */
    public function getBytesSaved()
    {
        return $this->getBytesBefore() - $this->getBytesAfter();
    }

    /**
     * Calculate the percentage saved.
     *
     * @return null|string
     */
    public function getPercentageSaved()
    {
        $bytesSaved = $this->getBytesSaved();

        if ($bytesSaved == 0) {
            return '0 %';
        } else {
            return round(($bytesSaved / $this->getBytesBefore()) * 100) . ' %';
        }
    }

    /**
     * Shows the time ago in human readable format when this image was processed.
     *
     * @return string
     */
    public function getTimeAgo()
    {
        return $this->_helper->timeAgo($this->getProcessedAt());
    }

    /**
     * Delete all models
     *
     * @return $this
     */
    public function deleteAll()
    {
        $collection = $this->getCollection();

        Mage::getSingleton('core/resource_iterator')->walk($collection->getSelect(), array( function ($args) {
            Mage::getModel('tig_tinypng/image')->load($args['row']['image_id'])->delete();
        }));

        return $this;
    }

    /**
     * Delete all models
     *
     * @return $this
     */
    public function deleteTest()
    {
        $collection = $this->getCollection();
        $collection->addFieldToFilter('is_test', '1');

        Mage::getSingleton('core/resource_iterator')->walk($collection->getSelect(), array( function ($args) {
            Mage::getModel('tig_tinypng/image')->load($args['row']['image_id'])->delete();
        }));

        return $this;
    }
}
