<?php
class Tiny_CompressImages_Test_Framework_Tiny_Test_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var null
     */
    protected $_instance = null;

    /**
     * Resets and restarts Magento.
     */
    public static function resetMagento()
    {
        Mage::reset();

        Mage::setIsDeveloperMode(false);
        Mage::app(
            'admin',
                'store',
                array(
                    'config_model' => 'Tiny_CompressImages_Test_Framework_Tiny_Test_Config'
                )
        )->setResponse(new Tiny_CompressImages_Test_Framework_Tiny_Test_Http_Response());

        $handler = set_error_handler(function() {});

        set_error_handler(function($errno, $errstr, $errfile, $errline) use ($handler) {
            if (E_WARNING === $errno
                && 0 === strpos($errstr, 'include(')
                && substr($errfile, -19) == 'Varien/Autoload.php'
            ) {
                return null;
            }
            return call_user_func(
                $handler, $errno, $errstr, $errfile, $errline
            );
        });
    }

    public function prepareFrontendDispatch()
    {
        $store = Mage::app()->getDefaultStoreView();
        $store->setConfig('web/url/redirect_to_base', false);
        $store->setConfig('web/url/use_store', false);
        $store->setConfig('advanced/modules_disable_output/Enterprise_Banner', true);

        Mage::app()->setCurrentStore($store->getCode());

        $this->registerMockSessions();
    }

    public function registerMockSessions($modules = null)
    {
        if (!is_array($modules)) {
            $modules = array('core', 'customer', 'checkout', 'catalog', 'reports');
        }

        foreach ($modules as $module) {
            $class = "$module/session";
            $sessionMock = $this->getMockBuilder(
                               Mage::getConfig()->getModelClassName($class)
                           )->disableOriginalConstructor()
                            ->getMock();
            $sessionMock->expects($this->any())
                        ->method('start')
                        ->will($this->returnSelf());
            $sessionMock->expects($this->any())
                        ->method('init')
                        ->will($this->returnSelf());
            $sessionMock->expects($this->any())
                        ->method('getMessages')
                        ->will($this->returnValue(
                            Mage::getModel('core/message_collection')
                        ));
            $sessionMock->expects($this->any())
                        ->method('getSessionIdQueryParam')
                        ->will($this->returnValue(
                            Mage_Core_Model_Session_Abstract::SESSION_ID_QUERY_PARAM
                        ));
            $sessionMock->expects($this->any())
                        ->method('getCookieShouldBeReceived')
                        ->will($this->returnValue(false));
            $this->setSingletonMock($class, $sessionMock);
            $this->setModelMock($class, $sessionMock);
        }

        $cookieMock = $this->getMock('Mage_Core_Model_Cookie');
        $cookieMock->expects($this->any())
                   ->method('get')
                   ->will($this->returnValue(serialize('dummy')));
        Mage::unregister('_singleton/core/cookie');
        Mage::register('_singleton/core/cookie', $cookieMock);

        // mock visitor log observer
        $logVisitorMock = $this->getMock('Mage_Log_Model_Visitor');
        $this->setModelMock('log/visitor', $logVisitorMock);

        /**
         * Fix enterprise catalog permissions issue
         */
        $factoryName = 'enterprise_catalogpermissions/permission_index';
        $className = Mage::getConfig()->getModelClassName($factoryName);
        if (class_exists($className)) {
            $mockPermissions = $this->getMock($className);
            $mockPermissions->expects($this->any())
                            ->method('getIndexForCategory')
                            ->withAnyParameters()
                            ->will($this->returnValue(array()));

            $this->setSingletonMock($factoryName, $mockPermissions);
        }
    }

    /**
     * @param string $modelClass
     * @param object $mock
     *
     * @return $this
     */
    public function setModelMock($modelClass, $mock)
    {
        $this->getConfig()->setModelMock($modelClass, $mock);

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
        $this->getConfig()->setResourceModelMock($modelClass, $mock);

        return $this;
    }

    /**
     * @param string $modelClass
     * @param object $mock
     *
     * @return $this
     */
    public function setSingletonMock($modelClass, $mock)
    {
        $registryKey = '_singleton/' . $modelClass;

        Mage::unregister($registryKey);
        Mage::register($registryKey, $mock);

        return $this;
    }

    /**
     * @param $modelClass
     *
     * @return mixed
     */
    public function getSingletonMock($modelClass)
    {
        $registryKey = '_singleton/' . $modelClass;

        return Mage::registry($registryKey);
    }

    /**
     * @param string $resourceModelClass
     * @param object $mock
     *
     * @return $this
     */
    public function setResourceSingletonMock($resourceModelClass, $mock)
    {
        $registryKey = '_resource_singleton/' . $resourceModelClass;

        Mage::unregister($registryKey);
        Mage::register($registryKey, $mock);

        return $this;
    }

    /**
     * @param string $helperClass
     * @param object $mock
     *
     * @return $this
     */
    public function setHelperMock($helperClass, $mock)
    {
        $registryKey = '_helper/' . $helperClass;

        Mage::unregister($registryKey);
        Mage::register($registryKey, $mock);

        return $this;
    }

    /**
     * @return Tiny_CompressImages_Test_Framework_Tiny_Test_Config
     */
    public function getConfig()
    {
        return Mage::getConfig();
    }

    /**
     * Create the models with the provided data.
     *
     * @param $modelName
     * @param $data
     */
    public function createModels($modelName, $data)
    {
        foreach ($data as $row) {
            $model = Mage::getModel($modelName);
            $model->setData($row);
            $model->save();
        }
    }

    /**
     * Sets a protected property to the provided value.
     *
     * @param      $property
     * @param      $value
     * @param null $instance
     *
     * @return $this
     */
    public function setProperty($property, $value, $instance = null)
    {
        if ($instance === null) {
            $instance = $this->_instance;
        }

        $reflection = new ReflectionObject($instance);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($instance, $value);

        return $this;
    }
}
