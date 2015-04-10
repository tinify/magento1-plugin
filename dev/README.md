# Development

The development process is described using OS X own Apache and PHP configuration.
Magento has its files dispersed over the filesystem. The idea is to put all
non-Magento code in the lib directory for easy reuse and location.
The integration with Magento is done in the usual Magento directories.

## Create MySQL database

## Download Magento

Go to https://www.magentocommerce.com/products/downloads/magento/
Download
 - Full Release
 - Sample Data

## Decompress both archives and put the contents in this directory:
 % tar xjf ~/Downloads/magento-sample-data-1.9.0.0.tar.bz2 && mv magento-sample-data-1.9.0.0/* . && rmdir magento-sample-data-1.9.0.0
 % tar xjf ~/Downloads/magento-1.9.1.0.tar-2015-02-09-10-12-12.bz2 && rsync --recursive magento/ . && rm -r magento
 % mysql -u root magento < magento_sample_data_for_1.9.0.0.sql
 % find . -type d | xargs chmod 777
 % find . -type f | xargs chmod 666

## Add host
Add host in Apache configuration for this directory

## Install mcrypt library (and other configuration tools if not already installed)
 % brew install mcrypt

## Compile PHP mcrypt extension

Download PHP source of the version you are running
 % php --version
and extract source somewhere

Go to /ext/mcrypt in the uncompressed dir
 % phpize && ./configure && make

Your extension is .libs/mcrypt.so

Add in your php.ini the following line where all the dynamic extensions are loaded
extension=<FULL_PATH_TO_EXTENSION>/mcrypt.so

## Reload Apache
sudo apachectl restart

## Install magento
Go to host and follow instructions (use 127.0.0.1 for host not localhost)

## How to go to admin
http://HOST/admin (when enabled RewriteEngine)
http://HOST/index.php/admin