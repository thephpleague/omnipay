---
layout: default
permalink: installation/
title: Installation
---

Installation
============

Omnipay is installed via [Composer](http://getcomposer.org/). To install all officially
supported gateways, simply add the following to your `composer.json` file:

~~~ javascript
{
    "require": {
        "omnipay/omnipay": "~2.0"
    }
}
~~~

Alternatively, you can require individual gateways:

~~~ javascript
{
    "require": {
        "omnipay/paypal": "~2.0"
    }
}
~~~ 

Next, run composer to update your dependencies:

~~~ bash
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar update
~~~