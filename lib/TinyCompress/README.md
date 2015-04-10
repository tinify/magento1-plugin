# Compress JPEG & PNG Images with Magento

This extension automatically compresses your Magento images that are generated
in the cache folder.

## Advantages

- Your site will load faster
- You will save bandwidth and hosting costs
- Compression happens on-the-fly in the background
- The extension is compatible with serving images through a CDN
- No cron jobs or scheduled tasks needed

## Extended Magento Product Catalog Image from core with extra code

Right now we have extended Mage_Catalog_Model_Product_Image method from
Magento version 1.9.1.0 with the new Dispatch Event and changed the default JPEG
quality to 95%.

## Magento core feature request

We have asked Magento to include the extra line of code so the function rewrite
can be removed again. But we have not recieved any response yet.

