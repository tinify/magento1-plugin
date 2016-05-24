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
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) 2015 Total Internet Group B.V. (http://www.tig.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_TinyPNG_Test_Framework_TIG_Test_Config extends Mage_Core_Model_Config
{
    /**
     * @var array
     */
    protected $_mockModels = array();

    /**
     * @var array
     */
    protected $_mockResourceModels = array();

    /**
     * @param string $modelClass
     * @param object $mock
     *
     * @return $this
     */
    public function setModelMock($modelClass, $mock)
    {
        $this->_mockModels[$modelClass] = $mock;
        return $this;
    }

    /**
     * @param string $modelClass
     * @param object $mock
     *
     * @return $this
     */
    public function setResourceModelMock($modelClass, $mock)
    {
        $this->_mockResourceModels[$modelClass] = $mock;
        return $this;
    }

    /**
     * @param string $modelClass
     * @param array  $constructArguments
     *
     * @return false|Mage_Core_Model_Abstract
     */
    public function getModelInstance($modelClass = '', $constructArguments = array())
    {
        $modelClass = (string) $modelClass;

        if (array_key_exists($modelClass, $this->_mockModels)) {
            return $this->_mockModels[$modelClass];
        }

        return parent::getModelInstance($modelClass, $constructArguments);
    }

    /**
     * Get resource model object by alias
     *
     * @param   string $modelClass
     * @param   array $constructArguments
     * @return  object
     */
    public function getResourceModelInstance($modelClass='', $constructArguments = array())
    {
        $modelClass = (string) $modelClass;

        if (array_key_exists($modelClass, $this->_mockResourceModels)) {
            return $this->_mockResourceModels[$modelClass];
        }

        return parent::getResourceModelInstance($modelClass, $constructArguments);
    }
}