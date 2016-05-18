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
     * Constructor
     */
    public function __construct() {
        require_once(Mage::getBaseDir('lib') . '/tinify-php/lib/Tinify.php');

        spl_autoload_register( array($this, 'load'), true, true );
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

        /**
         * Replace the namespace prefix with the base directory, replace namespace
         * separators with directory separators in the relative class name, append
         * with .php
         */
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

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
            //If this exception is thrown, the validation has failed
            return false;
        }

        return true;
    }

    /**
     * TODO: compress images through this function
     *
     * @param null $store
     */
    public function compress($store = null) {

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