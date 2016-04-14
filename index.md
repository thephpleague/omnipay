---
layout: default
permalink: /
title: Introduction
---

Introduction
============

[![Source Code](http://img.shields.io/badge/source-league/omnipay-blue.svg?style=flat-square)](https://github.com/thephpleague/omnipay)
[![Build Status](https://img.shields.io/travis/thephpleague/omnipay-common/master.svg?style=flat-square)](https://travis-ci.org/thephpleague/omnipay-common)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://github.com/thephpleague/omnipay/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/omnipay/omnipay.svg?style=flat-square)](https://packagist.org/packages/omnipay/omnipay)
[![Total Downloads](https://poser.pugx.org/omnipay/omnipay/d/total.png)](https://packagist.org/packages/omnipay/omnipay)

Omnipay is a payment processing library for PHP. It has been designed based on
ideas from [Active Merchant](http://activemerchant.org/), plus experience implementing
dozens of gateways for [CI Merchant](https://github.com/expressodev/ci-merchant). It has a clear and consistent API,
is fully unit tested, and even comes with an example application to get you started.

## Why use Omnipay?

So, why use Omnipay instead of a gateway's official PHP package/example code?

- Because you can learn one API and use it in multiple projects using different payment gateways
- Because if you need to change payment gateways you won't need to rewrite your code
- Because most official PHP payment gateway libraries are a mess
- Because most payment gateways have exceptionally poor documentation
- Because you are writing a shopping cart and need to support multiple gateways

## Upgrading to 2.0

If you are upgrading from a pre-2.0 version of Omnipay, please note that the
project has now been split into multiple packages. There have also been some
changes to how gateway instances are created. See the
[full release notes](/changelog/)
for more details.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the GitHub issue tracker
for the appropriate package, or better yet, fork the library and submit a pull request.

## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please head on over to the [mailing list](https://groups.google.com/forum/#!forum/omnipay)
and point out what you do and don't like, or fork the project and make suggestions. **No issue is too small.**
