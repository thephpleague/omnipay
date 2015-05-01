---
layout: default
permalink: changelog/
title: Changelog
---

Changelog
=========

## v2.0.0 - 2013-11-17

### Package Separation

As of 2.0, Omnipay has been split into separate packages. Core functionality is contained within the [omnipay/common](https://github.com/omnipay/common) repository, and all gateways have their own repositories. This means that if your project only requires on a single gateway, you can load it without installing all of the other gateways. All officially supported gateways can be found under the [Omnipay GitHub organization](http://github.com/omnipay).

If you want to install all gateways, you can still use the `omnipay/omnipay` metapackage in `composer.json`:

~~~ javascript
{
    "require": {
        "omnipay/omnipay": "~2.0"
    }
}
~~~

Alternatively, if you want to migrate to an individual gateway, simply change your `composer.json` file to reference the specific gateway (`omnipay/common` will be included for you automatically):

~~~ javascript
{
    "require": {
        "omnipay/paypal": "~2.0"
    }
}
~~~

### Breaking Changes

The `GatewayFactory` class can no longer be called in a static fashion. To help those who want to use dependency injection, you can now create an instance of GatewayFactory:

~~~ php
$factory = new GatewayFactory();
$gateway = $factory->create('PayPal_Express');
~~~

The following code is invalid and will no longer work:

~~~ php
$gateway = GatewayFactory::create('PayPal_Express'); // will cause PHP error!
~~~

If you want to continue to use static methods for simplicity, you can use the new Omnipay class:

~~~ php
// at the top of your PHP file
use Omnipay\Omnipay;

// further down when you need to create the gateway
$gateway = Omnipay::create('PayPal_Express');
~~~

Behind the scenes, this will create a GatewayFactory instance for you and call the appropriate method on it.

### Additions

**Omnipay now supports sending line-item data to gateways.** Currently this is only supported by the PayPal gateway. Line item details can be added to a request like so:

~~~ php
$request->setItems(array(
    array('name' => 'Food', 'quantity' => 1, 'price' => '40.00'),
    array('name' => 'Drinks', 'quantity' => 2, 'price' => '6.00'),
));
~~~

For more details, see the [pull request](https://github.com/omnipay/omnipay/pull/154).

**Omnipay now also supports modifying request data before it is sent to the gateway.**. This allows you to send arbitrary custom data with a request, even if Omnipay doesn't support a parameter directly. To modify the request data, instead of calling `send()` directly on the request, you may use the new `sendData()` method:

~~~ php
// standard method - send default data
$response = $request->send();

// new method - get and send custom data
$data = $request->getData();
$data['customParameter'] = true;

$response = $request->sendData($data);
~~~

For more details, see the [pull request](https://github.com/omnipay/omnipay/pull/162).
