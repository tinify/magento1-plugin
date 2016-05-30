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
class ImageTest extends Tiny_CompressImages_Test_Framework_TIG_Test_TestCase
{
    /**
     * @var Tiny_CompressImages_Model_Image
     */
    protected $_instance = null;

    /**
     * Delete all models before testing.
     */
    public function setup()
    {
        Mage::getModel('tig_tinypng/image')->deleteAll();

        $this->_instance = Mage::getModel('tig_tinypng/image');
    }

    /**
     * Test data.
     *
     * @return array
     */
    public function testGetStatisticsDataProvider()
    {
        $now = Zend_Date::now();

        return array(
            array(
                array(
                    array(
                        'path' => '/media/catalog/product/cache/1/image/c96a280f94e22e3ee3823dd0a1a87606/a/c/acj003_2.jpg',
                        'hash_before' => md5(uniqid()),
                        'hash_after' => md5(uniqid()),
                        'bytes_before' => 100,
                        'bytes_after' => 50,
                        'used_as_source' => 3,
                        'processed_at' => $now->subMonth(1),
                    ),
                    array(
                        'path' => '/media/catalog/product/cache/1/image/c96a280f94e22e3ee3823dd0a1a87606/a/c/acj003_2.jpg',
                        'hash_before' => md5(uniqid()),
                        'hash_after' => md5(uniqid()),
                        'bytes_before' => 100,
                        'bytes_after' => 75,
                        'used_as_source' => 3,
                        'processed_at' => Varien_Date::now(),
                    ),
                ),
                array(
                    'percentage' => '25 %',
                    'percentage_all_year' => '38 %',
                    'greatest_saving' => '25',
                    'greatest_saving_all_year' => '50',
                    'bytes_saved' => 25,
                    'bytes_saved_all_year' => 75,
                    'images_count' => 1,
                    'images_count_all_year' => 2,
                )
            ),
            array(
                array(
                    array(
                        'path' => '/media/catalog/product/cache/1/image/c96a280f94e22e3ee3823dd0a1a87606/d/e/dec001_1.jpg',
                        'hash_before' => md5(uniqid()),
                        'hash_after' => md5(uniqid()),
                        'bytes_before' => 500,
                        'bytes_after' => 400,
                        'used_as_source' => 1,
                        'processed_at' => Varien_Date::now(),
                    ),
                ),
                array(
                    'percentage' => '20 %',
                    'percentage_all_year' => '20 %',
                    'greatest_saving' => '20',
                    'greatest_saving_all_year' => '20',
                    'bytes_saved' => 100,
                    'bytes_saved_all_year' => 100,
                    'images_count' => 1,
                    'images_count_all_year' => 1,
                )
            ),
            array(
                array(
                    array(
                        'path' => '/media/catalog/product/cache/1/image/c96a280f94e22e3ee3823dd0a1a87606/t/r/troe008.jpg',
                        'hash_before' => md5(uniqid()),
                        'hash_after' => md5(uniqid()),
                        'bytes_before' => 200,
                        'bytes_after' => 150,
                        'used_as_source' => 1,
                        'processed_at' => Varien_Date::now(),
                    ),
                ),
                array(
                    'percentage' => '25 %',
                    'percentage_all_year' => '25 %',
                    'greatest_saving' => '25',
                    'greatest_saving_all_year' => '25',
                    'bytes_saved' => 50,
                    'bytes_saved_all_year' => 50,
                    'images_count' => 1,
                    'images_count_all_year' => 1,
                )
            ),
        );
    }

    /**
     * Test the results provided by the getStatistics method.
     *
     * @dataProvider testGetStatisticsDataProvider
     */
    public function testGetStatistics($models, $results)
    {
        $this->createModels('tig_tinypng/image', $models);

        $stats = $this->_instance->getStatistics();
        $this->assertEquals($results['percentage'], $stats->getPercentageSaved());
        $this->assertEquals($results['bytes_saved'], $stats->getBytesSaved());
        $this->assertEquals($results['images_count'], $stats->getImagesCount());
        $this->assertEquals($results['greatest_saving'], $stats->getGreatestSaving());

        $stats = $this->_instance->getStatistics(['current_month' => false]);
        $this->assertEquals($results['percentage_all_year'], $stats->getPercentageSaved());
        $this->assertEquals($results['bytes_saved_all_year'], $stats->getBytesSaved());
        $this->assertEquals($results['images_count_all_year'], $stats->getImagesCount());
        $this->assertEquals($results['greatest_saving_all_year'], $stats->getGreatestSaving());
    }

    /**
     * Test the fetching of a model by a hash.
     */
    public function testGetByHash()
    {
        $this->createModels('tig_tinypng/image', array(
            array(
                'path' => '1.jpg',
                'hash_before' => '54eab696c5c868b669076216ede66a9f',
                'hash_after' => '636734773941b236c82c9285b11d7f6c',
                'processed_at' => Varien_Date::now(),
            ),
            array(
                'path' => '2.jpg',
                'hash_before' => 'e79f197d28bebd30ba1bc571c2cc075a',
                'hash_after' => 'df72ed8dbbdca9c440ff6d3182fb0070',
                'processed_at' => Varien_Date::now(),
            ),
            array(
                'path' => '3.jpg',
                'hash_before' => '59c50fe6455c85fe00b169bde7cb1962',
                'hash_after' => '8e160a4f7fa67e9126269bedcc81c640',
                'processed_at' => Varien_Date::now(),
            ),
        ));

        $model = $this->_instance->getByHash('54eab696c5c868b669076216ede66a9f');
        $this->assertEquals('1.jpg', $model->getPath());

        $model = $this->_instance->getByHash('df72ed8dbbdca9c440ff6d3182fb0070');
        $this->assertEquals('2.jpg', $model->getPath());

        $model = $this->_instance->getByHash('foobar');
        $this->assertNull($model);
    }

    /**
     * Test if the addUsedAsSource works properly.
     */
    public function testAddUsedAsSource()
    {
        for ($i = 1; $i <= 3; $i++) {
            $this->_instance->addUsedAsSource();
            $this->assertEquals($i, $this->_instance->getUsedAsSource());
        }
    }

    /**
     * Test the getImageUrl method.
     */
    public function testGetImageUrl()
    {
        $path = '/media/catalog/product/image.jpg';
        $this->_instance->setPath($path);

        $this->assertEquals(
            Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . trim($path, '/'),
            $this->_instance->getImageUrl()
        );
    }

    /**
     * Test the getBytesSaved method.
     */
    public function testGetBytesSaved()
    {
        $this->_instance->setBytesBefore(500);
        $this->_instance->setBytesAfter(300);

        $this->assertEquals(200, $this->_instance->getBytesSaved());
    }

    /**
     * Test the getPercentageSaved method.
     */
    public function testGetPercentageSaved()
    {
        $this->_instance->setBytesBefore(500);
        $this->_instance->setBytesAfter(400);

        $this->assertEquals('20 %', $this->_instance->getPercentageSaved());

        $this->_instance->setBytesBefore(500);
        $this->_instance->setBytesAfter(500);

        $this->assertEquals('0 %', $this->_instance->getPercentageSaved());
    }

    /**
     * Test the deleteTest method.
     */
    public function testDeleteAll()
    {
        $models = array();
        for ($i = 0; $i < 10; $i++) {
            $models[] = array(
                'hash_before' => md5(uniqid()),
                'hash_after' => md5(uniqid()),
            );
        }

        $this->createModels('tig_tinypng/image', $models);

        $this->assertEquals(10, $this->_instance->getCollection()->getSize());

        $this->_instance->deleteAll();

        $this->assertEquals(0, $this->_instance->getCollection()->getSize());
    }
}