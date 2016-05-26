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
 * Class TIG_Adcurve_Helper_Data
 */
class TIG_TinyPNG_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * The name of the logfile.
     *
     * @var string
     */
    protected $logFile = 'TIG_TinyPNG.log';

    /**
     * @param $msg
     * @param $type
     *
     * @return $this;
     */
    public function log($msg, $type = null, $store = null)
    {
        $logginModes = $this->_loggingArray($store);

        /**
         * Always log exceptions. $msg should be an instanceof Exception!
         */
        if ($msg instanceof Exception) {
            $type = 'exception';
            $msg = $msg->__toString();
        }

        if (!in_array($type, $logginModes)) {
            return $this;
        }

        Mage::log($msg, null, $this->logFile, true);

        return $this;
    }

    /**
     * @param $store
     *
     * @return array
     */
    protected function _loggingArray($store)
    {
        switch (TIG_TinyPNG_Helper_Config::getLoggingMode($store))
        {
            case 'only_exceptions':
                $logginArray = array('exception');
                break;
            case 'fail_and_exceptions':
                $logginArray = array('error', 'exception');
                break;
            case 'all':
                $logginArray = array('info', 'error', 'exception');
                break;
            case 'off':
            default:
                $logginArray = array('');
        }

        return $logginArray;
    }

    /**
     * Returns whether the log file exists or not
     *
     * @return bool
     */
    public function getLogFileExists()
    {
        $filePath = $this->getLogFilePath();

        if (!@file_exists($filePath)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the file path of the log file
     *
     * @return string
     */
    public function getLogFilePath()
    {
        $logDir = Mage::getBaseDir('log');

        $filePath = $logDir . DS . $this->logFile;

        return $filePath;
    }

    /**
     * Get the name of the logfile.
     *
     * @return string
     */
    public function getLogFilename()
    {
        return $this->logFile;
    }

    /**
     * Copied from https://gist.github.com/jblyberg/1572386
     *
     * @param     $datefrom
     * @param int $dateto
     *
     * @return string
     */
    public function timeAgo($datefrom, $dateto = -1) {
        $datefrom = strtotime($datefrom);
        if ($datefrom <= 0) {
            return $this->__('A long time ago');
        }
        if ($dateto == -1) {
            $dateto = time();
        }
        $difference = $dateto - $datefrom;
        if ($difference < 60) {
            $interval = "s";
        } elseif ($difference >= 60 && $difference < 60 * 60) {
            $interval = "n";
        } elseif ($difference >= 60 * 60 && $difference < 60 * 60 * 24) {
            $interval = "h";
        } elseif ($difference >= 60 * 60 * 24 && $difference < 60 * 60 * 24 * 7) {
            $interval = "d";
        } elseif ($difference >= 60 * 60 * 24 * 7 && $difference < 60 * 60 * 24 * 30) {
            $interval = "ww";
        } elseif ($difference >= 60 * 60 * 24 * 30 && $difference < 60 * 60 * 24 * 365) {
            $interval = "m";
        } elseif ($difference >= 60 * 60 * 24 * 365) {
            $interval = "y";
        }
        switch ($interval) {
            case "m":
                $months_difference = floor($difference / 60 / 60 / 24 / 29);
                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                    $months_difference++;
                }
                $datediff = $months_difference;
                if ($datediff == 12) {
                    $datediff--;
                }
                $res = ($datediff == 1) ? $this->__('%s month ago', $datediff) : $this->__('%s months ago', $datediff);
                break;
            case "y":
                $datediff = floor($difference / 60 / 60 / 24 / 365);
                $res = ($datediff == 1) ? $this->__('%s year ago', $datediff) : $this->__('%s years ago', $datediff);
                break;
            case "d":
                $datediff = floor($difference / 60 / 60 / 24);
                $res = ($datediff == 1) ? $this->__('%s day ago', $datediff) : $this->__('%s days ago', $datediff);
                break;
            case "ww":
                $datediff = floor($difference / 60 / 60 / 24 / 7);
                $res = ($datediff == 1) ? $this->__('%s week ago', $datediff) : $this->__('%s weeks ago', $datediff);
                break;
            case "h":
                $datediff = floor($difference / 60 / 60);
                $res = ($datediff == 1) ? $this->__('%s hour ago', $datediff) : $this->__('%s hours ago', $datediff);
                break;
            case "n":
                $datediff = floor($difference / 60);
                $res = ($datediff == 1) ? $this->__('%s minute ago', $datediff) : $this->__('%s minutes ago', $datediff);
                break;
            case "s":
                $datediff = $difference;
                $res = ($datediff == 1) ? $this->__('%s second ago', $datediff) : $this->__('%s seconds ago', $datediff);
                break;
        }

        return $res;
    }

    /**
     * Retrieve a human readable file size.
     * Copied from http://jeffreysambells.com/2012/10/25/human-readable-filesize-php
     *
     * @param $bytes
     *
     * @return string
     */
    public function fileSize($bytes)
    {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf('%.0f ', $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}