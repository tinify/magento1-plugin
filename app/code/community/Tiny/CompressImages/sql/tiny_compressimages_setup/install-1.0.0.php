<?php
/**
 * @var Tiny_CompressImages_Model_Resource_Setup $installer
 */
$installer = $this;

$installer->startSetup();

/** @var Varien_Db_Adapter_Interface $connection */
$connection = $installer->getConnection();

$tableName = $installer->getTable('tiny_compressimages/image');
if (!$connection->isTableExists($tableName)) {
    $table = $connection
        ->newTable($tableName)
        ->addColumn(
            'image_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true,
            ),
            'ID'
        )
        ->addColumn(
            'path',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => false,
            ),
            'The path of this image'
        )
        ->addColumn(
            'path_optimized',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => false,
            ),
            'The optimized path of this image'
        )
        ->addColumn(
            'image_type',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => false,
            ),
            'The type of this image'
        )
        ->addColumn(
            'hash_before',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => false,
            ),
            'The hash of the image before processing'
        )
        ->addColumn(
            'hash_after',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => false,
            ),
            'The hash of the image after processing'
        )
        ->addColumn(
            'bytes_before',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'The number of bytes before processing'
        )
        ->addColumn(
            'bytes_after',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'The number of bytes after processing'
        )
        ->addColumn(
            'used_as_source',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'How many times is this file used as source'
        )
        ->addColumn(
            'is_test',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'Is this image processed in test mode?'
        )
        ->addColumn(
            'parent_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Has parent image and therefore its copied'
        )
        ->addColumn(
            'compressed_before',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Is copressed before and therefore not compressed again'
        )
        ->addColumn(
            'processed_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'Processed At'
        );

    $connection->createTable($table);
}

$tableName = $installer->getTable('tiny_compressimages/totals');
if (!$connection->isTableExists($tableName)) {
    $table = $connection
        ->newTable($tableName)
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true,
            ),
            'ID'
        )
        ->addColumn(
            'total_bytes_before',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'The number of bytes before processing'
        )
        ->addColumn(
            'total_bytes_after',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'The number of bytes after processing'
        )
        ->addColumn(
            'total_compressions',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
            ),
            'The number of compressions'
        )
        ->addColumn(
            'date_from',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'First day of the month'
        )
        ->addColumn(
            'date_to',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'Last day of the month'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'Processed At'
        );

    $connection->createTable($table);
}

/**
 * Move the api_key setting to it's new location
 */
$installer->moveConfigSettingInDb('compress_images/settings/api_key', 'tiny_compressimages/settings/api_key');

/**
 * In the old module the different image types are 4 different options, in the new module this is only 1 option
 */
$images = array(
    'compress_image' => 'image',
    'compress_small_image' => 'small_image',
    'compress_thumbnail' => 'thumbnail',
    'compress_other_images' => 'media',
);

$allowedTypes = array();
foreach ($images as $from => $to) {
    $value = Mage::getConfig('compress_images/image_sizes/' . $from);

    if ($value) {
        $allowedTypes[] = $to;
    }
}

if (count($allowedTypes)) {
    try {
        $connection->insert(
            $installer->getTable('core/config_data'),
            array(
                'scope'    => 'default',
                'scope_id' => '0',
                'value'    => implode(',', $allowedTypes),
                'path'     => 'tiny_compressimages/settings/product_compression',
            )
        );
    } catch (Exception $e) {
        Mage::helper('tiny_compressimages')->log($e);
    }
}

$installer->endSetup();
