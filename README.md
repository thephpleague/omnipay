# Tala Payments

This is a proposal for a PHP 5.3, PSR-2 and Composer compliant payment processing library.
Any feedback is welcome - please raise a github issue to discuss, or fork the project and send a pull request.

This library has been designed based on experience using [Active Merchant](http://activemerchant.org/),
plus personal experience implementing dozens of gateways for [CI Merchant](http://ci-merchant.org/). However,
it would be great to get as much community support behind this as possible, so we can all save ourselves the
wasted effort of re-implementing countless obscure gateways.

# Package Layout

Tala Payments will be split into a core library (`tala-payemnts-core`) which provides the abstract classes and common functionality,
plus a separate library for each supported payment gateway, which depends on the core. For example, if a user
only requires PayPal Express in their application, they can simply require `tala-payments-paypal` in their
`composer.json` file. This separate gateways method has two advantages:

* Developers who only need a single payment gateway don't need to include unnecessary files in their project
* Obscure gateways can be implemented separately, and supported by third parties, without needing to be included in the main project

We can make a `tala-payments-all` package which depends on all the officially supported payment gateways,
so it is easy for cart developers to include all payment gateways at once.

For an example gateway, see the [tala-payments-paypal](https://github.com/adrianmacneil/tala-payments-paypal) repository.

# Payment Gateways

All payment gateways must implement [\Tala\Payments\GatewayInterface](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Payments/GatewayInterface.php), and usually
extend [\Tala\Payments\AbstractGateway](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Payments/AbstractGateway.php) for basic functionality.

Gateways are initialized like so:

    $settings = array(
        'username' => 'adrian',
        'password' => '12345',
        'currency' => 'USD',
    );
    $gateway = new PayPalExpress($settings);

Where `$settings` is an array of gateway-specific options. The gateway can also be initialized after creation
by calling `initialize()`:

    $gateway->initialize($settings);

Finally, gateway settings can be changed individually using getters and setters:

    $gateway->setCurrency('NZD');
    $username = $gateway->getUsername();

Most settings will be gateway specific, however `currency` is a setting available in all payment gateways, and
it can also be overridden for individual payments.

Generally most payment gateways can be classed as one of two main types:

* Off-site gateways such as PayPal Express, where the customer is redirected to a third party site to enter payment details
* On-site (merchant-hosted) gateways such as PayPal Pro, where the customer enters their credit card details on your site

However, there are some gateways such as SagePay Direct, where you take credit card details on site, then optionally redirect
if the customer's card supports 3D Secure authentication. Therefore, there is no point differentiating between the two types of
gateway (other than by the methods they support).

# Credit Card / Payment Form Input

User form input will be directed to a [\Tala\Payments\CreditCard](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Payments/CreditCard.php) object. This provides a safe way to mass-assign user input.
The `CreditCard` object will have the following fields:

* firstName
* lastName
* number
* expiryMonth
* expiryYear
* cvv
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

Like a gateway, the `CreditCard` object can be intialized when it is created, or by calling the `initialize()` method:

    $card = new CreditCard($formInput);
    $card->initialize($formInput); // only need to use one of these methods

You can also update the fields using getters and setters:

    $number = $card->getNumber();
    $card->setFirstName('Adrian');

Gateways are free to extend the `CreditCard` object if they need extra fields, but most gateways should be fine with the
default fields. Even off-site gateways will probably make use of a `CreditCard` object, because often you want to pass
the customer billing details to the gateway.

# Gateway Methods

The main methods implemented by gateways are:

* `authorize($request)` - authorize an amount on the customer's card
* `completeAuthorize($request)` - handle return from off-site gateways after authorization
* `capture($request)` - capture an amount you have previously authorized
* `purchase($request)` - authorize and immediately capture an amount on the customer's card
* `completePurchase($request)` - handle return from off-site gateways after purchase
* `refund($request)` - refund an already processed transaction
* `void($request)` - generally can only be called up to 24 hours after submitting a transaction

On-site gateways do not need to implement the `completeAuthorize` and `completePurchase` methods. If any gateway does not support
certain features (such as refunds), it will throw a [\Tala\Payments\Exception\BadMethodCallException](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Payments/Exception/BadMethodCallException.php).

All gateway methods take a [\Tala\Payments\Request](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Payments/Request.php)
object. The request object holds various details about the transaction:


    $card = new CreditCard();
    $request = new Request();
    $request->setAmount(1000);
    $request->setSource($card);
    $request->setReturnUrl('https://example.com/payment/complete');
    $response = $gateway->authorize($request);

In payment requests, the `$source` variable can be either a `CreditCard` object, or a string `token` which has been stored from a previous
transaction for certain gateways (see the Token Billing section below).

When calling the `completeAuthorize` or `completePurchase` methods, the exact same arguments should be provided as when you made the initial
`authorize` or `purchase` call (some gateways will need to verify for example the actual amount paid equals the amount requested).

At this point, you may be wondering the difference between gateway `$settings`, `CreditCard` fields, and `Request` fields:

* Gateway `$settings` are settings which apply to all payments (like the gateway username and password). Generally you will store these in a configuration file or in the database.
* CreditCard fields are data which the user supplies. For example, you want the user to specify their `firstName` and `billingCountry`, but you don't want a user to specify the payment `currency` or `returnUrl`.
* Request fields are used for any payment-specific options, which are not set by the customer. For example, the payment `transactionId` and `returnUrl`, and you can also override the `currency` here if you need to.

# The Payment Response

The payment response must implement [\Tala\Payments\ResponseInterface](https://github.com/adrianmacneil/tala-payments/blob/master/src/Tala/Payments/ResponseInterface.php). There are two main types of response:

* Payment was successful (standard response)
* Website must redirect to off-site payment form (redirect response)

## Successful Response

For a successful responses, a reference will normally be generated, which can be used to capture or refund the transaction
at a later date. The following methods are always available:

    $reference = $response->getGatewayReference();
    $mesage = $response->getMessage();

In addition, most gateways will override the response object, and provide access to any extra fields returned by the gateway.

## Redirect Response

The redirect response is further broken down by whether the customer's browser must redirect using GET (RedirectResponse object), or
POST (FormRedirectResponse). These could potentially be combined into a single response class, with a `getRedirectMethod()`.

After processing a payment, the cart should check whether the response requires a redirect, and if so, redirect accordingly:
    
    $response = $gateway->purchase(1000, $card);
    if ($response->isRedirect()) {
        $response->redirect(); // this will automatically forward the customer
    } else {
        // payment is complete
    }

The customer isn't automatically forwarded on, because often the cart or developer will want to customize the redirect method
(or if payment processing is happening inside an AJAX call they will want to return JS to the browser instead).

To display your own redirect page, simply call `getRedirectUrl()` on the response, then display it accordingly:

    $url = $response->getRedirectUrl();
    // for a form redirect, you can also call the following method:
    $data = $response->getFormData(); // associative array of fields which must be posted to the redirectUrl

# Error Handling

If there is an error with the payment, an Exception will be thrown. Standard exceptions will be provided, or gateways can define their
own exceptions. All payments should be wrapped in a try-catch block:

    try {
        $response = $gateway->purchase(1000, $card);
        // mark order as complete
    } catch (\Tala\Payments\Exception $e) {
        // display error to the user
    }

# Token Billing

I'm still working on functions for token billing. Most likely gateways will be able to implement the following methods:

* `store($card)` - returns a response object which includes a `token`, which can be used for future transactions
* `unstore($token)` - remove a stored card, not all gateways support this method

Feel free to suggest better names for these methods.

# Recurring Billing

At this stage, I don't think there will be support for automatic recurring payments functionality (asice from token billing).
This is because there is likely far too many differences between how each gateway handles recurring billing profiles.
Also in most cases token billing will cover your needs. I'm open to suggestions on this.

# I18n

I'm not sure what the best option for cross-framework localization is. What do most generic Composer packages use these days?
Perhaps it is easier if it's not part of this library?

# Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please raise a Github issue, and point out what you do and don't like, or fork the project and make any suggestions.
