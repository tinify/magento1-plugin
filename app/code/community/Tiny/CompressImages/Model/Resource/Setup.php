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
class Tiny_CompressImages_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    /**
     * Copy a config setting from an old xpath to a new xpath directly in the database, rather than using Magento config
     * entities.
     *
     * @param string $fromXpath
     * @param string $toXpath
     *
     * @return $this
     */
    public function moveConfigSettingInDb($fromXpath, $toXpath)
    {
        $conn = $this->getConnection();

        try {
            $select = $conn->select()
                ->from($this->getTable('core/config_data'))
                ->where('path = ?', $fromXpath);

            $result = $conn->fetchAll($select);
            foreach ($result as $row) {
                try {
                    /**
                     * Copy the old setting to the new setting.
                     *
                     * @todo Check if the row already exists.
                     */
                    $conn->insert(
                        $this->getTable('core/config_data'),
                        array(
                            'scope'    => $row['scope'],
                            'scope_id' => $row['scope_id'],
                            'value'    => $row['value'],
                            'path'     => $toXpath
                        )
                    );
                } catch (Exception $e) {
                    Mage::helper('tig_tinypng')->log($e);
                }
            }
        } catch (Exception $e) {
            Mage::helper('tig_tinypng')->log($e);
        }

        return $this;
    }

    /**
     * Check if the specified xpath exists.
     *
     * @param $xpath
     *
     * @return bool
     */
    public function configExists($xpath)
    {
        $conn = $this->getConnection();

        try {
            $select = $conn->select()
                ->from($this->getTable('core/config_data'))
                ->where('path = ?', $xpath);

            $result = $conn->fetchAll($select);
            foreach ($result as $row) {
                return true;
            }
        } catch (Exception $e) {
            Mage::helper('tig_tinypng')->log($e);
        }

        return false;
    }
}