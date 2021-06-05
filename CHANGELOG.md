Changelog
=========

## v3.2 - 2021-06-01

Omnipay 3.2 is compatible with PHP8. This is done by upgrading the test suite to PHPUnit 8/9, with the release of omnipay/tests v4 and omnipay/common v3.1. This change is primarily for gateway developers, to make it possible to actually test PHP8, but they will need to upgrade their tests to use PHPUnit 9 (the currently supported PHPUnit version). 
## v3.1 - 2020-10-29

Omnipay 3.1 uses Guzzle 7 by default (using the Guzzle 7 adapter). This doesn't change omnipay-common because they will work with any compatible Http Client.
The minimum PHP versions is bumped to 7.2 because of this.

## v3.0 - 2018-05-14

Omnipay 3.0 focuses on separation of the HTTP Client, to be independent of Guzzle. 
This release brings compatibility with the latest Symfony 3+4 and Laravel 5. 
The breaking changes for applications using Omnipay are kept to a minimum.

The `omnipay/omnipay` package name has been changed to `league/omnipay`

### Upgrading applications from Omnipay 2.x to 3.x

#### Breaking changes
 - The `redirect()` method no calls `exit()` after sending the content. This is up to the developer now.
 - An HTTP Client is required. Guzzle will be installed when using `league/omnipay`, 
 but otherwise you need to required your own implementation (see [PHP HTTP Clients](http://docs.php-http.org/en/latest/clients.html))
- The `omnipay/omnipay` package name has been changed to `league/omnipay` and no longers installs all the gateways directly.

#### Added
 - It is now possible to use `setAmountInteger(integer $value)` to set the amount in the base units of the currency.
 - Support for [Money for PHP](http://moneyphp.org/) objects are added, by using `setMoney(Money $money)` the Amount and Currency are set.

### Upgrading Gateways from 2.x to 3.x

The primary difference is the HTTP Client. We are now using HTTPlug (http://httplug.io/) but rely on our own interface.

### Breaking changes
- Change typehint from Guzzle ClientInterface to `Omnipay\Common\Http\ClientInterface`
- `$client->get('..')`/`$client->post('..')` etc are removed, you can call `$client->request('GET', '')`.
- No need to call `$request->send()`, requests are sent directly.
- Instead of `$client->createRequest(..)` you can create+send the request directly with `$client->request(..)`.
- When sending a JSON body, convert the body to a string with `json_encode()` and set the correct Content-Type.
- The response is a PSR-7 Response object. You can call `$response->getBody()->getContents()` to get the body as string.
- `$response->json()` and `$response->xml()` are gone, but you can implement the logic directly.
- An HTTP Client is no longer added by default by `omnipay/common`, but `league/omnipay` will add Guzzle. 
Gateways should not rely on Guzzle or other clients directly.
- `$body` should be a string (eg. `http_build_query($data)` or `json_encode($data)` instead of just `$data`).
- The `$headers` parameters should be an `array` (not `null`, but can be empty)

Examples:
```php
// V2 XML:
 $response = $this->httpClient->post($this->endpoint, null, $data)->send();
 $result = $httpResponse->xml();

// V3 XML:
 $response = $this->httpClient->request('POST', $this->endpoint, [], http_build_query($data));
 $result = simplexml_load_string($httpResponse->getBody()->getContents());
```

```php
// Example JSON request:

 $response = $this->httpClient->request('POST', $this->endpoint, [
     'Accept' => 'application/json',
     'Content-Type' => 'application/json',
 ], json_encode($data));
 
 $result = json_decode($response->getBody()->getContents(), true);
```

#### Testing changes

PHPUnit is upgraded to PHPUnit 6. Common issues:

- `setExpectedException()` is removed

```php
// PHPUnit 5:
$this->setExpectedException($class, $message);

// PHPUnit 6:
$this->expectException($class);
$this->expectExceptionMessage($message);
```

- Tests that do not perform any assertions, will be marked as risky. This can be avoided by annotating them with ` @doesNotPerformAssertions`

- You should remove the `Mockery\Adapter\Phpunit\TestListener` in phpunit.xml.dist


## v2.0.0 - 2013-11-17

### Package Separation

As of 2.0, Omnipay has been split into separate packages. Core functionality is contained within the [omnipay/common](https://github.com/omnipay/common) repository, and all gateways have their own repositories. This means that if your project only requires on a single gateway, you can load it without installing all of the other gateways. All officially supported gateways can be found under the [Omnipay GitHub organization](//github.com/omnipay).

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
