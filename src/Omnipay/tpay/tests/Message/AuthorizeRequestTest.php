<?php

namespace Omnipay\Tpay\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    /**
     * @var AuthorizeRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount'   => '10.00',
                'currency' => 'USD',
                'hashType' => 'sha1',
            )
        );
    }

    public function testGetData()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount'   => '10.00',
                'currency' => 'USD',
                'hashType' => 'sha1',
            )
        )->setOrderId('123')
         ->setToken('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24')
         ->setDescription('Payment for order X');

        $data = $this->request->getData();

        $this->assertSame('presale', $data['method']);
        $this->assertSame('10.00', $data['amount']);
        $this->assertSame('840', $data['currency']);
        $this->assertSame('123', $data['order_id']);
        $this->assertSame('Payment for order X', $data['desc']);
        $this->assertSame('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24', $data['cli_auth']);

    }
}
