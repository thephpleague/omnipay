# Changelog :zap:

# 1.0.0 (2013-06-24)

`amount` is now specified as a decimal (i.e. `'10.00'` instead of `1000`
to represent $10.00. Passing integers will throw an exception, reminding you
to update your application code. To be clear, that means instead of this:

    $gateway->purchase(array('amount' => 1000, 'currency' => 'USD'));

You must now create the request like so:

    $gateway->purchase(array('amount' => '10.00', 'currency' => 'USD'));

This should avoid any further confusion over how to specify the amount.

*   Added Mollie payment gateway
*   Added `notifyUrl` and `issuer` fields to example app
