---
layout: default
permalink: api/charging/
title: Charging
---

Charging
========

The main methods implemented by gateways are:

* `purchase($options)` - authorize and immediately capture an amount on the customer's card
* `completePurchase($options)` - handle return from off-site gateways after purchase

On-site gateways do not need to implement the `completePurchase` method, and will throw `BadMethodCallException` if called.

When calling the `completeAuthorize` or `completePurchase` methods, the exact same arguments should be provided as
when you made the initial `authorize` or `purchase` call (some gateways will need to verify for example the actual
amount paid equals the amount requested). The only parameter you can omit is `card`.