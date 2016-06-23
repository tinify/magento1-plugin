# Compress JPEG & PNG images for Magento 1

Make your Magento 1 store faster by compressing your JPEG and PNG images.

This plugin automatically optimizes your images by integrating with the
popular image compression services TinyJPG and TinyPNG.

Learn more about TinyJPG and TinyPNG at https://tinypng.com/.

Do you use Magento 2? Install our Magento 2 plugin instead:
https://packagist.org/packages/tinify/magento2

## How doest it work?

When you view a product in your webshop, Magento creates different image sizes in its cache folders. This extension will compress these images for you automatically. Any image sizes that are exact duplicates of each other will only be compressed once.

Your product images are uploaded to the TinyJPG or TinyPNG service and analyzed to apply the best possible compression. Based on the content of the image an optimal strategy is chosen. The result is sent back to your Magento webshop and saved in your public media folder.

On average JPEG images are compressed by 40-60% and PNG images by 50-80% without visible loss in quality. Your webshop will load faster for your visitors, and you’ll save storage space and bandwidth!

## Getting started

Obtain your free API key from https://tinypng.com/developers. The first 500
compressions per month are completely free, no strings attached! As each
product will be shown in different sizes, between 50 and 100 products can be
uploaded to your Magento webshop and compressed for free. You can also change
which of types of image sizes should be compressed.

If you’re a heavy user, you can compress additional images for a small
additional fee per image by upgrading your account. You can keep track of the
amount of compressions in the Magento 1 configuration section.

## Installation

The extensions is still in beta, it is not available as Magento Connect package yet. Installation can be done using [Modman](https://github.com/colinmollenhour/modman). From your Magento 1 installation directory:

    git clone git@github.com:tinify/magento1-plugin.git .modman/Tiny_CompressImages
    modmany deploy-all

## Contact us

Got questions or feedback? Let us know! Contact us at support@tinypng.com.

## Information for plugin contributors

* PHP 5.4 or newer.
* MySQL 5.4 or newer (integration tests).
* Magento 1.8.0.0 or newer.
* phpunit 4.8 or newer.

### Running the unit tests

From the .modman/Tiny_CompressImages directory:

    phpunit

## License

This software is licensed under the MIT License. [View the license](LICENSE).