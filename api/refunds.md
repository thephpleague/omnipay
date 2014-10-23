---
layout: default
permalink: api/refunds/
title: Refunds
---

Refunds
=======

The main method implemented by gateways are:

* `refund($options)` - refund an already processed transaction

If any gateway does not support certain features (such as refunds), it will throw `BadMethodCallException`.