---
layout: default
permalink: /
title: Introduction
---

Introduction
============

[![Build Status](https://travis-ci.org/thephpleague/omnipay-common.png?branch=master)](https://travis-ci.org/thephpleague/omnipay-common)
[![Latest Stable Version](https://poser.pugx.org/omnipay/omnipay/version.png)](https://packagist.org/packages/omnipay/omnipay)
[![Total Downloads](https://poser.pugx.org/omnipay/omnipay/d/total.png)](https://packagist.org/packages/omnipay/omnipay)

Omnipay is a payment processing library for PHP. It has been designed based on
ideas from [Active Merchant](http://activemerchant.org/), plus experience implementing
dozens of gateways for [CI Merchant](http://ci-merchant.org/). It has a clear and consistent API,
is fully unit tested, and even comes with an example application to get you started.

## Why use Omnipay instead of a gateway's official PHP package/example code?

* Because you can learn one API and use it in multiple projects using different payment gateways
* Because if you need to change payment gateways you won't need to rewrite your code
* Because most official PHP payment gateway libraries are a mess
* Because most payment gateways have exceptionally poor documentation
* Because you are writing a shopping cart and need to support multiple gateways

## Important Note: Upgrading from < 2.0

If you are upgrading from a pre-2.0 version of Omnipay, please note that the
project has now been split into multiple packages. There have also been some
changes to how gateway instances are created. See the
[full release notes](https://github.com/thephpleague/omnipay/releases/tag/v2.0.0)
for more details.