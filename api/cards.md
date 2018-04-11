---
layout: default
permalink: api/cards/
title: Cards
---

Cards
=====

User form input is directed to an [CreditCard](https://github.com/thephpleague/omnipay-common/blob/master/src/Common/CreditCard.php)
object. This provides a safe way to accept user input.

The `CreditCard` object has the following fields:

~~~ php
[
    'firstName',
    'lastName',
    'number',
    'expiryMonth',
    'expiryYear',
    'startMonth',
    'startYear',
    'cvv',
    'issueNumber',
    'type',
    'billingAddress1',
    'billingAddress2',
    'billingCity',
    'billingPostcode',
    'billingState',
    'billingCountry',
    'billingPhone',
    'shippingAddress1',
    'shippingAddress2',
    'shippingCity',
    'shippingPostcode',
    'shippingState',
    'shippingCountry',
    'shippingPhone',
    'company',
    'email'
]
~~~

Even off-site gateways make use of the `CreditCard` object, because often you need to pass
customer billing or shipping details through to the gateway.

The `CreditCard` object can be initialized with untrusted user input via the constructor.
Any fields passed to the constructor which are not recognized will be ignored.

~~~ php
$formInputData = array(
    'firstName' => 'Bobby',
    'lastName' => 'Tables',
    'number' => '4111111111111111',
);
$card = new CreditCard($formInputData);
~~~

You can also just pass the form data array directly to the gateway, and a `CreditCard` object
will be created for you.

CreditCard fields can be accessed using getters and setters:

~~~ php
$number = $card->getNumber();
$card->setFirstName('Adrian');
~~~

If you submit credit card details which are obviously invalid (missing required fields, or a number
which fails the Luhn check), [InvalidCreditCardException](https://github.com/thephpleague/omnipay-common/blob/master/src/Common/Exception/InvalidCreditCardException.php)
will be thrown.  You should validate the card details using your framework's validation library
before submitting the details to your gateway, to avoid unnecessary API calls.

For on-site payment gateways, the following card fields are generally required:

* firstName
* lastName
* number
* expiryMonth
* expiryYear
* cvv

You can also verify the card number using the Luhn algorithm by calling `Helper::validateLuhn($number)`.
