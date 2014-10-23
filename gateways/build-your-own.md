---
layout: default
permalink: gateways/build-your-own/
title: Build your own gateway
---

Build your own gateway
======================

Omnipay is a collection of packages which all depend on the
[omnipay/common](https://github.com/thephpleague/omnipay-common) package to provide
a consistent interface. There are no dependencies on official payment gateway PHP packages -
we prefer to work with the HTTP API directly. Under the hood, we use the popular and powerful
[Guzzle](http://guzzlephp.org/) library to make HTTP requests.

New gateways can be created by cloning the layout of an existing package. When choosing a
name for your package, please don't use the `omnipay` vendor prefix, as this implies that
it is officially supported. You should use your own username as the vendor prefix, and prepend
`omnipay-` to the package name to make it clear that your package works with Omnipay.
For example, if your GitHub username was `santa`, and you were implementing the `giftpay`
payment library, a good name for your composer package would be `santa/omnipay-giftpay`.

## Make your gateway official

If you want to transfer your gateway to the `omnipay` GitHub organization and add it
to the list of officially supported gateways, please open a pull request on the 
[omnipay/common](https://github.com/thephpleague/omnipay-common) package. Before new gateways will
be accepted, they must have 100% unit test code coverage, and follow the conventions
and code style used in other Omnipay gateways.