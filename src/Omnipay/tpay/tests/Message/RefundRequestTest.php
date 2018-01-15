<?php

namespace Omnipay\Tpay\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @var RefundRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $data = array(
            'amount'   => '10.00',
            'currency' => 'USD',
            'hashType' => 'sha1',
        );

        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request
            ->initialize($data)
            ->setDescription('Refund for order X')
            ->setToken('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24')
            ->setTransactionId('t59c28295aeb071b0cf6471b24f727f6456998de');

        $data = $this->request->getData();

        $this->assertSame('refund', $data['method']);
        $this->assertSame('10.00', $data['amount']);
        $this->assertSame('840', $data['currency']);
        $this->assertSame('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24', $data['cli_auth']);
        $this->assertSame('Refund for order X', $data['desc']);
        $this->assertSame('t59c28295aeb071b0cf6471b24f727f6456998de', $data['sale_auth']);

    }

}
