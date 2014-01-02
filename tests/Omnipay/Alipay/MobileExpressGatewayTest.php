<?php

namespace Omnipay\Alipay;

use Omnipay\Tests\GatewayTestCase;

class MobileExpressGatewayTest extends GatewayTestCase
{

    /**
     * @var MobileExpressGateway $gateway
     */
    protected $gateway;

    protected $options;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = new MobileExpressGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPartner('20880127040');
        $this->gateway->setKey('sc1n78r0faswga7jjrpf6o');
        $this->gateway->setSellerEmail('example@qq.com');
        $this->gateway->setNotifyUrl('https://www.example.com/notify');
        $this->gateway->setReturnUrl('https://www.example.com/return');
        $this->gateway->setShowUrl('https://www.example.com/return1');
        $this->gateway->setPrivateKey(
            '-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDELAccoy5epvo9TEKr4sXLVNbM0ZXAu24G9z/k2D3SHtFuONCh
H1O5nF73332hSA1k1x/nexGNAMlot/H6IlucdRaL8zHcSA5AKVw0iCnD9BoVnXCG
tElayPXQeLgtEP5FAJ9Ba1w28UWTgkgTj8dAFwYxADiAMm9i4LfWMtay0wIDAQAB
AoGAdxtASivtqHx7bSJTTKeIblcZgAw0f2uDwHj4a0q75krd361RRrKNlCGUK62f
SoBD2Zkf/tzjIBh9MT6WBcg8lCZ1UaNxwmXoyZ76G3IrjeJd02foRd648v663Top
fTjoKjv2KrzSmUu2Km4uE+NZqFSL+Jd1z0DwHbhfd0I8BGECQQDvCg7muPcEUi7o
3GpK0QVsk8EzP1Q8fdlebpr+FcCvfL5uTMIY6z27fO4p0dONJL8s9gV7r464XekP
KnfImBnRAkEA0hdScmQaZuZLUsJwhWWPRmYraJ3FsplvPJ5opt+zeemgiW2sxOfx
cVY2eFSt0qstmqau/FbSFRjCyrs8hlAHYwJAezorLmPh65dWWXLvVLxmWG/fJEVW
K30RJq5MNnoOSCk9nmzxjpkOzO19+YgSz+tGpq35a6a4I3E+KTRSZdWLUQJAaMPa
iFKk29VRkHaHt+26Mcf3M5cho/thfiAcXcLF9DBtrrpzYkmrm/H6/ax0dc6I0kr2
jb0ZzA1p7cDK4Mt9swJACh0wFnEQvfFBVUZo/zWW5nEBnVQ4l1QhfG6DoWJJA866
jdamyj2vQOFHLE2qpD+wprkUa86FJsdaEcuKjUl1lw==
-----END RSA PRIVATE KEY-----'
        );
        $this->options = array(
            'out_trade_no' => '2014010122390001',
            'subject'      => 'test',
            'total_fee'    => '0.01',
        );
    }

    public function testPurchase()
    {
        /**
         * @var PurchaseResponse $response
         */
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectData());
    }
}
