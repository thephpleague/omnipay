---
layout: default
permalink: gateways/build-your-own/
title: Build your own driver
---

Build your own driver
=====================

Omnipay is a collection of packages which all depend on the
[omnipay/common](https://github.com/thephpleague/omnipay-common) package to provide
a consistent interface. There are no dependencies on official payment gateway PHP packages -
we prefer to work with the HTTP API directly. Under the hood, we use the popular and powerful
[PHP-HTTP](http://docs.php-http.org/en/latest/index.html) library to make HTTP requests. 
A [Guzzle](http://guzzlephp.org/) adapter is required by default, when using `omnipay/omnipay`.

New gateways can be created by cloning the layout of an existing package. When choosing a
name for your package, please don't use the `omnipay` vendor prefix, as this implies that
it is officially supported. You should use your own username as the vendor prefix, and prepend
`omnipay-` to the package name to make it clear that your package works with Omnipay.
For example, if your GitHub username was `santa`, and you were implementing the `giftpay`
payment library, a good name for your composer package would be `santa/omnipay-giftpay`.


## Omnipay Terminology

- **Merchant Site** - The website or application that initiates the payment. Typically this will be an ecommerce store or some other online system that needs to take payments from customers.
- **Merchant** - The owner or operator of the Merchant Site.
- **Payment Gateway** - The remote payment processing system that handles the communication and transfer of funds between the Merchant Site, the customer's bank, and the Merchant's bank. These are typically large companies that handle many thousands of payments for many Merchants every day. See https://en.wikipedia.org/wiki/Payment_gateway for more details.
- **Driver** - The code written to extend the core Omnipay functionality so that it can communicate with a specifc Payment Gateway. There are several 'official' Omnipay drivers, and many others written by individuals. If the Payment Gateway that you wish to use doesn't currently have a driver written for it, you can create your own and share it with the community. 
- **Transaction** - An single attempt (successful or otherwise) to make a payment.

## Omnipay Conventions

When developing your own payment gateway driver, it's worth remembering that ideally the person using your driver should be able to switch between drivers easily, without modifying their own code. With that in mind, here are some conventions that are used in Omnipay:
 - **transactionId vs transactionReference** - `transactionId` is the Merchant's reference to the transaction - so typically the ID of the payment record in the Merchant Site's database. `transactionReference` is the Payment Gateway's reference to the transaction. They will usually generate a unique reference for each payment attempt a customer makes. It is common practice to store this value in the Merchant Site's database, so that the transaction can be cross-referenced with the Payment Gateway's own records. Some Omnipay drivers also rely on this value being available in order to process refunds or repeat payments.
 - **returnUrl vs notifyUrl** - `returnUrl` is used by drivers when they need to tell the Payment Gateway where to redirect the customer following a transaction. Typically this is used by off-site 'redirect' gateway integrations. `notifyUrl` is used by drivers to tell the Payment Gateway where to send their server-to-server notification, informing the Merchant Site about the outcome of a transaction. The `notifyUrl` will typically be a script on the Merchant Site that handles the updating of the database to record whether a payment was successful or not.
