---
layout: default
permalink: api/error-handling/
title: Error handling
---

Error handling
==============

You can test for a successful response by calling `isSuccessful()` on the response object. If there
was an error communicating with the gateway, or your request was obviously invalid, an exception
will be thrown. In general, if the gateway does not throw an exception, but returns an unsuccessful
response, it is a message you should display to the customer. If an exception is thrown, it is
either a bug in your code (missing required fields), or a communication error with the gateway.

You can handle both scenarios by wrapping the entire request in a try-catch block:

~~~ php
try {
    $response = $gateway->purchase(['amount' => '10.00', 'card' => $card])->send();
    if ($response->isSuccessful()) {
        // mark order as complete
    } elseif ($response->isRedirect()) {
        $response->redirect();
    } else {
        // display error to customer
        exit($response->getMessage());
    }
} catch (\Exception $e) {
    // internal error, log exception and display a generic message to the customer
    exit('Sorry, there was an error processing your payment. Please try again later.');
}
~~~