---
layout: default
permalink: api/authorizing/
title: Authorizing
---

Authorizing
===========

The main methods implemented by gateways are:

* `authorize($options)` - Authorize an amount on the customer's card.
* `completeAuthorize($options)` - Handle return from off-site gateways after authorization. On-site gateways do not need to implement the `completeAuthorize` method, and will throw `BadMethodCallException` if called.
* `capture($options)` - Capture an amount you have previously authorized.

All gateway methods take an `$options` array as an argument. Each gateway differs in which
parameters are required, and the gateway will throw `InvalidRequestException` if you
omit any required parameters. All gateways will accept a subset of these options:

* card
* token
* amount
* currency
* description
* transactionId
* clientIp
* returnUrl
* cancelUrl

Pass the options through to the method like so:

~~~ php
$card = new CreditCard($formData);
$request = $gateway->authorize([
    'amount' => '10.00', // this represents $10.00
    'card' => $card,
    'returnUrl' => 'https://www.example.com/return',
]);
~~~

When calling the `completeAuthorize` or `completePurchase` methods, the exact same arguments should be provided as
when you made the initial `authorize` or `purchase` call (some gateways will need to verify for example the actual
amount paid equals the amount requested). The only parameter you can omit is `card`.


To summarize the various parameters you have available to you:

* Gateway settings (e.g. username and password) are set directly on the gateway. These settings apply to all payments, and generally you will store these in a configuration file or in the database.
* Method options are used for any payment-specific options, which are not set by the customer. For example, the payment `amount`, `currency`, `transactionId` and `returnUrl`.
* CreditCard parameters are data which the user supplies. For example, you want the user to specify their `firstName` and `billingCountry`, but you don't want a user to specify the payment `currency` or `returnUrl`.