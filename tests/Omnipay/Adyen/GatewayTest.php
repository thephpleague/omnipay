<?php
namespace Omnipay\Adyen;

require_once 'PHPUnit/Autoload.php';
$autoloader = require __DIR__.'/../../../../../autoload.php';
$autoloader->add('Omnipay', __DIR__ . '/../../');

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public $gateway;

    public function __construct()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function testPurchase()
    {
        $this->gateway->setPaymentAmount(10);
        $request = $this->gateway->purchase();
        $this->assertInstanceOf('Omnipay\Adyen\Message\PurchaseRequest', $request);
        $this->assertSame(10, $request->getPaymentAmount('paymentAmount'));
    }

    public function testPurchaseReturn()
    {
        $this->gateway->setPaymentAmount(10);
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf('Omnipay\Adyen\Message\CompletePurchaseRequest', $request);
        $this->assertSame(10, $this->gateway->getPaymentAmount('paymentAmount'));
    }
}
