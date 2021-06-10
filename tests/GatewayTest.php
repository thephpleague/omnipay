<?php

namespace Omnipay\Myfatoorah;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase {

    public function setUp() {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase() {
        $data                      = array();
        $data['Amount']            = '50';
        $data['OrderRef']          = '5342652460'; // order ID
        $data['Currency']          = 'KWD';
        $data['returnUrl']         = 'http://localomnipay.com/myfatoorah.php';
        $data['Card']['firstName'] = 'fname';
        $data['Card']['lastName']  = 'lname';
        $data['Card']['email']     = 'test@test.com';
//
// Do a purchase transaction on the gateway
        $request                   = $this->gateway->purchase($data)->send();
        if ($request->isSuccessful()) {
            $invoiceId   = $request->getTransactionReference();
            echo "Invoice Id = " . $invoiceId . "<br>";
            $redirectUrl = $request->getRedirectUrl();
            echo "Redirect Url = <a href='$redirectUrl' target='_blank'>" . $redirectUrl . "</a><br>";
        } else {
            echo $request->getMessage();
        }


//        $this->assertInstanceOf('Omnipay\Myfatoorah\Message\PurchaseRequest', $request);
//        $this->assertSame('789123', $request->getAccountId());
//        $this->assertSame('50.70', $request->getAmount());
    }

    public function testRetrievePayment() {
        $request  = $this->gateway->CompletePurchase([
            'paymentId' => '100202112414940102'
        ]);
        if ($request->isSuccessful()) {
            echo "<pre>";
            print_r($request->getPaymentStatus('5342652460' /* order ID */, '100202112414940102'));
        } else {
            echo $request->getMessage();
        }
        $this->assertInstanceOf('Omnipay\Myfatoorah\Message\CompletePurchase', $request);
        $this->assertSame('100202112414940102', $request->getPaymentId());
    }

}
