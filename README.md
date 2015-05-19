# TinyPNG image compression for Magento
Make your website faster by compressing your JPEG and PNG images.

This plugin automatically optimizes your images by integrating with the popular image compression services TinyJPG and TinyPNG.
You can download the plugin from http://www.magentocommerce.com/magento-connect/compress-jpeg-png-images.html.

Learn more about TinyPNG at https://tinypng.com.

## Contact us
Got questions or feedback? Let us know! Contact us at support@tinypng.com.

## Information for plugin contributors

The development process is described using OS X own Apache and PHP configuration.
Magento has its files dispersed over the filesystem. The idea is to put all
non-Magento code in the lib directory for easy reuse and location.
The integration with Magento is done in the usual Magento directories.

### Create MySQL database

### Download Magento

Go to https://www.magentocommerce.com/products/downloads/magento/
Download
 - Full Release
 - Sample Data

### Decompress both archives and put the contents in this directory:
```
tar xjf ~/Downloads/magento-sample-data-1.9.1.0.tar.bz2 && mv magento-sample-data-1.9.0.0/* . && rmdir magento-sample-data-1.9.0.0
```
```
tar xjf ~/Downloads/magento-1.9.1.0.tar-2015-02-09-10-12-12.bz2 && rsync --recursive magento/ . && rm -r magento
```
```
mysql -u root magento < magento_sample_data_for_1.9.0.0.sql
```
```
find . -type d | xargs chmod 777 ; find . -type f | xargs chmod 666
```

### Add host
Add host in Apache configuration for this directory

### Install mcrypt library (and other configuration tools if not already installed)
`brew install mcrypt`

### Compile PHP mcrypt extension

Download PHP source of the version you are running
```
php --version
```
and extract source somewhere

Go to /ext/mcrypt in the uncompressed dir
```
phpize && ./configure && make
```

Your extension is .libs/mcrypt.so

Add in your php.ini the following line where all the dynamic extensions are loaded
```
extension=<FULL_PATH_TO_EXTENSION>/mcrypt.so
```

### Reload Apache
```
sudo apachectl restart
```

### Install magento
Go to host and follow instructions (use 127.0.0.1 for host not localhost)

### How to go to admin
http://HOST/admin (when enabled RewriteEngine)
http://HOST/index.php/admin

## License
Copyright (C) 2015 Voormedia B.V.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

[View the complete license](lib/TinyCompress/LICENSE).
