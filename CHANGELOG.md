# Changelog :zap:

# v1.1.0 (2013-10-19)

* Paypal [BC BREAK]: Removed default value `1` for `noShipping` option and added `allowNote` option.
  To retain previous behavior, pass `'noShipping' => 1` when creating the request. (@aderuwe)
* Add TargetPay gateway (@aderuwe)
* MultiSafepay: Add purchase parameter (@aderuwe)
* MultiSafepay: Add support for directtransaction (@ruudk)
* Authorize.Net SIM: Add support for hash secret (@amsross)
* Authorize.Net AIM: Add extra response getters
* Sage Pay Direct: Don't pass state unless country is US

# v1.0.4 (2013-09-20)

* Update Pin gateway to support using JS tokens (@nagash)
* More tests (@johnkary)

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
