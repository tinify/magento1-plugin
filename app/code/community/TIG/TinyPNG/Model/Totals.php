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

 * @method string|null getEntityId();
 * @method $this setDateFrom(String $date);
 * @method string|null getDateFrom();
 * @method $this setDateTo(String $date);
 * @method string|null getDateTo();
 * @method $this setTotalBytesBefore(int $bytes);
 * @method int|null getTotalBytesBefore();
 * @method $this setTotalBytesAfter(int $bytes);
 * @method int|null getTotalBytesAfter();
 * @method $this setTotalCompressions(int $compressions);
 * @method string|null getTotalCompressions();
 * @method $this setUpdatedAt(String $date);
 * @method string|null getUpdatedAt();
 */
class TIG_TinyPNG_Model_Totals extends Mage_Core_Model_Abstract
{
    /**
     * Constructor load his parent.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Class constructor.
     */
    public function _construct()
    {
        $this->_init('tig_tinypng/totals');
    }

    /**
     * Gets the compression information over the total data.
     *
     * @return array
     */
    public function getTotalCompressionInformation()
    {
        $collection = $this->getCollection();

        $totalCompressions = 0;
        $totalBytesBefore  = 0;
        $totalBytesAfter   = 0;

        /** @var TIG_TinyPNG_Model_Totals $record */
        foreach ($collection as $record) {
            $totalCompressions = $record->getTotalCompressions() + $totalCompressions;
            $totalBytesBefore  = $record->getTotalBytesBefore()  + $totalBytesBefore;
            $totalBytesAfter   = $record->getTotalBytesAfter()   + $totalBytesAfter;
        }

        $bytesSaved      = $totalBytesBefore - $totalBytesAfter;
        $percantageSaved = 0;

        if ($bytesSaved !== 0) {
            $percantageSaved = round(($bytesSaved / $totalBytesBefore) * 100);
        }

        return array(
            'percentageSaved'   => $percantageSaved,
            'totalCompressions' => $totalCompressions
        );
    }
}