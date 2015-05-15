<?php

/*
* Tiny Compress Images - Magento Extension.
* Copyright (C) 2015 Voormedia B.V.
*
* This program is free software; you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the Free
* Software Foundation; either version 2 of the License, or (at your option)
* any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
* FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
* more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc., 51
* Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

require('TinyCompress' . DIRECTORY_SEPARATOR . 'class-tiny-exception.php');
require('TinyCompress' . DIRECTORY_SEPARATOR . 'class-tiny-php.php');
require('TinyCompress' . DIRECTORY_SEPARATOR . 'class-tiny-compress.php');
require('TinyCompress' . DIRECTORY_SEPARATOR . 'class-tiny-compress-curl.php');
require('TinyCompress' . DIRECTORY_SEPARATOR . 'class-tiny-compress-fopen.php');

class Tiny_CompressImages_Model_Observer
{
    public function compress(Varien_Event_Observer $observer)
    {
        $imageObject = $observer->getEvent()->getObject();
        $destinationSubdir = $imageObject->getDestinationSubdir();
        switch ($destinationSubdir) {
            case "image" :
                $allowCompression = Mage::getStoreConfig('compress_images/image_sizes/compress_image');
                break;
            case "small_image" :
                $allowCompression = Mage::getStoreConfig('compress_images/image_sizes/compress_small_image');
                break;
            case "thumbnail" :
                $allowCompression = Mage::getStoreConfig('compress_images/image_sizes/compress_thumbnail');
                break;
            default :
                $allowCompression = Mage::getStoreConfig('compress_images/image_sizes/compress_other_images');
                break;
        }
        if ($allowCompression) {
            $apiKey = Mage::getStoreConfig('compress_images/settings/api_key');
            if (!empty($apiKey)) {
                $newFile = $imageObject->getNewFile();
                $width = $imageObject->getWidth();
                $height = $imageObject->getHeight();
                $compressor = Tiny_Compress::get_compressor($apiKey);
                try {
                    $details = $compressor->compress_file($newFile);
                    $logDescription =
                        "Variant " . $destinationSubdir .
                        " allowed " . $allowCompression .
                        " width " . $width .
                        " height " . $height .
                        " API " . $apiKey .
                        " JSON response " . json_encode($details);
                } catch (Tiny_Exception $e) {
                    $logDescription = $e->get_error() . ': ' . $e->getMessage();
                }
            } else {
                $logDescription = "No API key found for compression.";
            }
        } else {
            $logDescription = "Variant {$destinationSubdir} not selected for compression.";
        }
        Mage::log($logDescription, null, 'image-compression.log');
    }
}
