# Tala Payments

[![Build Status](https://secure.travis-ci.org/adrianmacneil/tala-payments.png)](http://travis-ci.org/adrianmacneil/tala-payments)

Tala Payments is a PHP 5.3, PSR-2 and Composer compliant payment processing library.
It has been designed based on experience using [Active Merchant](http://activemerchant.org/),
plus experience implementing dozens of gateways for [CI Merchant](http://ci-merchant.org/).

This library is currently under development, and all feedback is welcome - please raise a github issue
to discuss, or fork the project and send a pull request.

# Package Layout

Tala Payments is a single package which provides abstract base classes and implementations
for all officially supported gateways. Unsupported gateways can either be added by forking
this package and submitting a pull request, or by distributing a separate library which depends
on this package and makes use of the base classes and consistent developer API.

# Payment Gateways

All payment gateways must implement [\Tala\GatewayInterface](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/GatewayInterface.php), and usually
extend [\Tala\AbstractGateway](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/AbstractGateway.php) for basic functionality.

The following gateways are already implemented:

* Authorize.Net AIM
* Authorize.Net SIM
* DPS PaymentExpress PxPay
* DPS PaymentExpress PxPost
* PayPal Express Checkout
* PayPal Payments Pro

Gateways are initialized like so:

```php
$settings = array(
    'username' => 'adrian',
    'password' => '12345',
);
$gateway = new \Tala\PayPalExpress\Gateway($settings);
```

Where `$settings` is an array of gateway-specific options. The gateway can also be initialized after creation
by calling `initialize()`:

```php
$gateway->initialize($settings);
```

Finally, gateway settings can be changed individually using getters and setters:

```php
$gateway->setUsername('adrian');
$username = $gateway->getUsername();
```

Most settings are gateway specific. To get an array of available gateway settings, call `getDefaultSettings()`:

```php
$settings = $gateway->getDefaultSettings();
// default settings array format:
array(
    'username' => '', // string variable
    'testMode' => false, // boolean variable
    'landingPage' => array('billing', 'login'), // enum variable
);
```

Generally most payment gateways can be classed as one of two main types:

* Off-site gateways such as PayPal Express, where the customer is redirected to a third party site to enter payment details
* On-site (merchant-hosted) gateways such as PayPal Pro, where the customer enters their credit card details on your site

However, there are some gateways such as SagePay Direct, where you take credit card details on site, then optionally redirect
if the customer's card supports 3D Secure authentication. Therefore, there is no point differentiating between the two types of
gateway (other than by the methods they support).

# Credit Card / Payment Form Input

User form input will be directed to a [\Tala\CreditCard](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/CreditCard.php) object. This provides a safe way to accept user input.
The `CreditCard` object has the following fields:

* firstName
* lastName
* number
* expiryMonth
* expiryYear
* startMonth
* startYear
* cvv
* type
* billingAddress1
* billingAddress2
* billingCity
* billingPostcode
* billingState
* billingCountry
* shippingAddress1
* shippingAddress2
* shippingCity
* shippingPostcode
* shippingState
* shippingCountry

Some gateways will require extra fields, so the `CreditCard` object can accept any parameter names. However,
the above parameters are standard names which most gateway are expected to use. Even off-site gateways make
use of the `CreditCard` object, because often you need to pass the customer billing details to the gateway.

Like a gateway, the `CreditCard` object can be intialized when it is created, or by calling the `initialize()` method:

```php
$card = new CreditCard($formInput);
$card->initialize($formInput); // you only need to use one of these methods
```

You can also update the fields using properties:

```php
$number = $card->number;
$card->firstName = 'Adrian';
```

# Gateway Methods

The main methods implemented by gateways are:

* `authorize($request, $source)` - authorize an amount on the customer's card
* `completeAuthorize($request)` - handle return from off-site gateways after authorization
* `capture($request)` - capture an amount you have previously authorized
* `purchase($request, $source)` - authorize and immediately capture an amount on the customer's card
* `completePurchase($request)` - handle return from off-site gateways after purchase
* `refund($request)` - refund an already processed transaction
* `void($request)` - generally can only be called up to 24 hours after submitting a transaction

On-site gateways do not need to implement the `completeAuthorize` and `completePurchase` methods. If any gateway does not support
certain features (such as refunds), it will throw a `BadMethodCallException`.

All gateway methods take a [\Tala\Request](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Request.php)
object. The request object holds various details about the transaction (each gateway requires different parameters):

```php
$source = new CreditCard();
$request = new Request();
$request->amount = 1000; // we will authorize $10
$request->returnUrl = 'https://example.com/payment/complete';
$response = $gateway->authorize($request, $source);
```

The `$source` variable can be either a `CreditCard` object, or a string token which has been
stored from a previous transaction for certain gateways (see the Token Billing section below).

When calling the `completeAuthorize` or `completePurchase` methods, the exact same arguments should be provided as
when you made the initial `authorize` or `purchase` call (some gateways will need to verify for example the actual
amount paid equals the amount requested).

At this point, you may be wondering the difference between gateway `$settings`, `CreditCard` fields, and `Request` fields:

* Gateway `$settings` are settings which apply to all payments (like the gateway username and password). Generally you will store these in a configuration file or in the database.
* CreditCard fields are data which the user supplies. For example, you want the user to specify their `firstName` and `billingCountry`, but you don't want a user to specify the payment `currency` or `returnUrl`.
* Request fields are used for any payment-specific options, which are not set by the customer. For example, the payment `amount`, `currency`, `transactionId` and `returnUrl`.

# The Payment Response

The payment response must implement [\Tala\ResponseInterface](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/ResponseInterface.php). There are two main types of response:

* Payment was successful (standard response)
* Website must redirect to off-site payment form (redirect response)

## Successful Response

For a successful responses, a reference will normally be generated, which can be used to capture or refund the transaction
at a later date. The following methods are always available:

```php
$reference = $response->getGatewayReference();
$mesage = $response->getMessage();
```

In addition, most gateways will override the response object, and provide access to any extra fields returned by the gateway.

## Redirect Response

The redirect response is further broken down by whether the customer's browser must redirect using GET (RedirectResponse object), or
POST (FormRedirectResponse). These could potentially be combined into a single response class, with a `getRedirectMethod()`.

After processing a payment, the cart should check whether the response requires a redirect, and if so, redirect accordingly:

```php
$response = $gateway->purchase(1000, $card);
if ($response->isRedirect()) {
    $response->redirect(); // this will automatically forward the customer
} else {
    // payment is complete
}
```

The customer isn't automatically forwarded on, because often the cart or developer will want to customize the redirect method
(or if payment processing is happening inside an AJAX call they will want to return JS to the browser instead).

To display your own redirect page, simply call `getRedirectUrl()` on the response, then display it accordingly:

```php
$url = $response->getRedirectUrl();
// for a form redirect, you can also call the following method:
$data = $response->getFormData(); // associative array of fields which must be posted to the redirectUrl
```

# Error Handling

If there is an error with the payment, an Exception is thrown. Standard exceptions are provided, or gateways
can define their own exceptions. All payments should be wrapped in a try-catch block:

```php
try {
    $response = $gateway->purchase(1000, $card);
    // mark order as complete
} catch (\Tala\Exception $e) {
    // display error to the user
}
```

# Token Billing

I'm still working on functions for token billing. Most likely gateways will be able to implement the following methods:

* `store($card)` - returns a response object which includes a `token`, which can be used for future transactions
* `unstore($token)` - remove a stored card, not all gateways support this method

Feel free to suggest better names for these methods.

# Recurring Billing

At this stage, I don't think there will be support for automatic recurring payments functionality (aside from token billing).
This is because there is likely far too many differences between how each gateway handles recurring billing profiles.
Also in most cases token billing will cover your needs. I'm open to suggestions on this.

# I18n

I'm not sure what the best option for cross-framework localization is. What do most generic Composer packages use these days?
Perhaps it is easier if it's not part of this library?

# Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please raise a Github issue, and point out what you do and don't like, or fork the project and make
suggestions. **No issue is too small.**
