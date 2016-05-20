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

/**
 * @method $this setPath(String $path);
 * @method string|null getPath();
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
 */
class TIG_TinyPNG_Model_Image extends Mage_Core_Model_Abstract
{
    /**
     * @var TIG_TinyPNG_Helper_Data
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
     * @return TIG_TinyPNG_Model_Image|null
     */
    public function getByHash($hash)
    {
        /** @var TIG_TinyPNG_Model_Resource_Image_Collection $tinyPNGModel */
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
}