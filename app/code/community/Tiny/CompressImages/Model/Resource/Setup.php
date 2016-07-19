<?php
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
                    Mage::helper('tiny_compressimages')->log($e);
                }
            }
        } catch (Exception $e) {
            Mage::helper('tiny_compressimages')->log($e);
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
            Mage::helper('tiny_compressimages')->log($e);
        }

        return false;
    }
}
