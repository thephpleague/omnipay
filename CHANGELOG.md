# Changelog :zap:

# v1.0.3 (2013-08-28)

* Stripe: Added fetchTransaction method (@cfreear)
* MultiSafepay: Assorted bug fixes (@aderuwe)
* Sage Pay: Fixed not sending correct card brand for MasterCard and Diners Club (@steveneaston)
* Sage Pay: Fixed RefundRequest not sending correct transaction type

# v1.0.2 (2013-07-23)

* Added MultiSafepay gateway
* Added PayPal Subject parameter
* PHPDoc fixes

# v1.0.1 (2013-06-29)

* Added Buckaroo gateway
* Added eWAY Rapid 3.0 gateway
* Added `getRedirectResponse()` method to `AbstractResponse`
* A few minor bug fixes & typos

# v1.0.0 (2013-06-24)

`amount` is now specified as a decimal (i.e. `'10.00'` instead of `1000`
to represent $10.00. Passing integers will throw an exception, reminding you
to update your application code. To be clear, that means instead of this:

    $gateway->purchase(array('amount' => 1000, 'currency' => 'USD'));

You must now create the request like so:

    $gateway->purchase(array('amount' => '10.00', 'currency' => 'USD'));

This should avoid any further confusion over how to specify the amount.

*   Added Mollie payment gateway
*   Added `notifyUrl` and `issuer` fields to example app
