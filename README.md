# Omnipay: MyFatoorah

**MyFatoorah driver for the Omnipay PHP payment processing library**

Just want to see some code?

```php
use Omnipay\Omnipay;

$data                      = array();
$data['Amount']            = '50';
$data['OrderRef']          = 'orderId-123'; 
$data['Currency']          = 'KWD';
$data['returnUrl']         = 'http://websiteurl.com/callback.php';
$data['Card']['firstName'] = 'fname';
$data['Card']['lastName']  = 'lname';
$data['Card']['email']     = 'test@test.com';
//
// Do a purchase transaction on the gateway
$transaction               = $gateway->purchase($data)->send();
if ($transaction->isSuccessful()) {
    $invoiceId   = $transaction->getTransactionReference();
    echo "Invoice Id = " . $invoiceId . "<br>";
    $redirectUrl = $transaction->getRedirectUrl();
    echo "Redirect Url = <a href='$redirectUrl' target='_blank'>" . $redirectUrl . "</a><br>";
} else {
    echo $transaction->getMessage();
}
```
In the callback, Get Payment status for a specific Payment ID

```php
$callBackData = ['paymentId' => '100202113817903101'];
$callback     = $gateway->completePurchase($callBackData)->send();
if ($callback->isSuccessful()) {
    echo "<pre>";
    print_r($callback->getPaymentStatus('orderId-123', '100202113817903101'));
} else {
    echo $callback->getMessage();
}
```
Refund a specific Payment ID

```php
$refundData = ['paymentId' => '100202113817903101', 'Amount'=>1];
$refund     = $gateway->refund($refundData)->send();
if ($refund->isSuccessful()) {
    echo "<pre>";
    print_r($refund->getRefundInfo());
} else {
    echo $refund->getMessage();
}

```