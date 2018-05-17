# Magento 2 Core Module by [Girit Interactive](https://www.girit-tech.com/)

This module is a required dependency for most of the Magento 2 modules from Girit Interactive.

---

## ✓ Install via composer (recommended)
Run the following command under your Magento 2 root dir:

```
composer require girit/magento2-module-core
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

## Install manually under app/code
Download & place the contents of this repository under {YOUR-MAGENTO2-ROOT-DIR}/app/code/Girit/Lazyload  
Then, run the following commands under your Magento 2 root dir:
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

---

http://www.girit-tech.com  
+972-3-9177788  
info@girit.biz  

Copyright © 2018 Girit-Interactive. All rights reserved.  

![Girit Interactive Logo](https://www.girit-tech.com/templates/images/logos/girit-flat.png)
