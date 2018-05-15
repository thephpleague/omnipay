---
layout: default
permalink: installation/
title: Installation
---

Installation
============

Omnipay is installed via [Composer](https://getcomposer.org/). 
For most uses, you will need to require `league/omnipay` and an individual gateway:

```
composer require league/omnipay:^3 omnipay/paypal
```

If you want to use your own HTTP Client instead of Guzzle (which is the default for `league/omnipay`),
you can require `omnipay/common` and any `php-http/client-implementation` (see [PHP Http](http://docs.php-http.org/en/latest/clients.html))

```
composer require omnipay/common:^3 omnipay/paypal php-http/buzz-adapter
```