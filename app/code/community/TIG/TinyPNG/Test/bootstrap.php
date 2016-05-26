<?php
if (strpos(__DIR__, '.modman') !== false) {
    require_once(dirname(__DIR__) . '/../../../../../../../app/Mage.php');
} else {
    require_once(__DIR__ . '/../../../../../../Mage.php');
}

ini_set('display_errors', true);
error_reporting(-1);
TIG_TinyPNG_Test_Framework_TIG_Test_TestCase::resetMagento();