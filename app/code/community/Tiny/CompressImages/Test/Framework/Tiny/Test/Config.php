<?php
class Tiny_CompressImages_Test_Framework_Tiny_Test_Config extends Mage_Core_Model_Config
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
