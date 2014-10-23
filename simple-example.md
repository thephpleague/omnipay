---
layout: default
permalink: simple-example/
title: Simple example
---

Simple example
==============

Here is a simple example of how to use Omnipay. As you can see, Omnipay has a consistent, well thought out API. As much as possible, we try to abstract the differences between the various payments gateways.

~~~ php
use Omnipay\Omnipay;

// Setup payment gateway
$gateway = Omnipay::create('Stripe');
$gateway->setApiKey('abc123');

// Example form data
$formData = [
    'number' => '4242424242424242',
    'expiryMonth' => '6',
    'expiryYear' => '2016',
    'cvv' => '123'
];

// Send purchase request
$response = $gateway->purchase(
    [
        'amount' => '10.00',
        'currency' => 'USD',
        'card' => $formData
    ]
)->send();

// Process response
if ($response->isSuccessful()) {
    
    // Payment was successful
    print_r($response);

} elseif ($response->isRedirect()) {
    
    // Redirect to offsite payment gateway
    $response->redirect();

} else {

    // Payment failed
    echo $response->getMessage();
}
~~~

## Example Application

An example application is also provided in the [omnipay/example](https://github.com/thephpleague/omnipay-example) repo.

~~~ bash
# Clone project
git clone https://github.com/thephpleague/omnipay-example.git omnipay-example

# Go to project directory
cd omnipay-example

# Install dependencies
composer install

# Run using the built-in PHP web server
php -S localhost:8000
~~~

For more information, see the [Omnipay example application](https://github.com/thephpleague/omnipay-example).