---
layout: default
permalink: api/token-billing/
title: Token billing
---

Token billing
==============

Token billing allows you to store a credit card with your gateway, and charge it at a later date.
Token billing is not supported by all gateways. For supported gateways, the following methods
are available:

* `createCard($options)` - returns a response object which includes a `cardReference`, which can be used for future transactions
* `updateCard($options)` - update a stored card, not all gateways support this method
* `deleteCard($options)` - remove a stored card, not all gateways support this method

Once you have a `cardReference`, you can use it instead of the `card` parameter when creating a charge:

~~~ php
$gateway->purchase(['amount' => '10.00', 'cardReference' => 'abc']);
~~~