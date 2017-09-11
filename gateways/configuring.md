---
layout: default
permalink: gateways/configuring/
title: Configuring gateways
---

Configuring gateways
====================

All payment gateways must implement [GatewayInterface](https://github.com/thephpleague/omnipay-common/blob/master/src/Omnipay/Common/GatewayInterface.php), and will usually
extend [AbstractGateway](https://github.com/thephpleague/omnipay-common/blob/master/src/Omnipay/Common/AbstractGateway.php) for basic functionality.

## Initialize a gateway

Gateways are created and initialized like so:

~~~ php
use Omnipay\Omnipay;

$gateway = Omnipay::create('PayPal_Express');
$gateway->setUsername('adrian');
$gateway->setPassword('12345');
~~~

Alternatively, multiple parameters can be initialized at once, directly from data:

~~~ php
...
$gateway->initialize([
    'username' => 'adrian',
    'password' => '12345',
]);
~~~

Setting parameters this way will start by taking the default parameters as a base,
and then merging your supplied parameters over the top.

## Gateway settings

Most settings are gateway specific. If you need to query a gateway to get a list of available settings, you can call `getDefaultParameters()`:

~~~ php
$settings = $gateway->getDefaultParameters();
// default settings array format:
array(
    'username' => '', // string variable
    'testMode' => false, // boolean variable
    'landingPage' => array('billing', 'login'), // enum variable, first item should be treated as default
);
~~~

## Gateway types

Generally most payment gateways can be classified as one of two types:

- **Off-site** gateways such as PayPal Express, where the customer is redirected to a third party site to enter payment details
- **On-site** (merchant-hosted) gateways such as PayPal Pro, where the customer enters their credit card details on your site

However, there are some gateways such as Sage Pay Direct, where you take credit card details on site, then optionally redirect if the customer's card supports 3D Secure authentication. Therefore, there is no point differentiating between the two types of gateway (other than by the methods they support).