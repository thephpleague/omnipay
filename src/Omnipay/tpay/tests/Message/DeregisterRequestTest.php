<?php

namespace Omnipay\Tpay\Message;

use Omnipay\Tests\TestCase;

class DeregisterRequestTest extends TestCase
{
    /**
     * @var DeregisterRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new DeregisterRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'hashType' => 'sha1',
        ));
    }

    public function testGetData()
    {
        $this->request = new DeregisterRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request
            ->initialize(array(
                'hashType' => 'sha1',
            ))
            ->setToken('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24')
            ->setLanguage('fr');
        $data = $this->request->getData();

        $this->assertSame('deregister', $data['method']);
        $this->assertSame('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24', $data['cli_auth']);
        $this->assertSame('fr', $data['language']);
    }
}
