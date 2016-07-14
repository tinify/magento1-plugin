<?php
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
class Tiny_CompressImages_Model_Totals extends Mage_Core_Model_Abstract
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
        $this->_init('tiny_compressimages/totals');
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

        /** @var Tiny_CompressImages_Model_Totals $record */
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
            'totalCompressions' => $totalCompressions,
            'bytesBefore'       => $this->formatBytes($totalBytesBefore),
            'bytesAfter'        => $this->formatBytes($totalBytesAfter),
        );
    }

    /**
     * @param     $bytes
     * @param int $precision
     *
     * @return string
     */
    function formatBytes($bytes, $precision = 2) {
        if ($bytes <= 0) {
            return '0 KB';
        }

        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $i = floor(log($bytes, 1024));
        $sum = round($bytes / pow(1024, $i), $precision);

        return $sum . ' ' . $units[$i];

    }
}
