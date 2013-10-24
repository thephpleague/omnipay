<?php

namespace Omnipay\Pacnet;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

		$this->gateway->setUserName('ernest');
		$this->gateway->setPRN('840033');
		$this->gateway->setPassword('all good men die young');
		$this->gateway->setTestMode(true);
	}

	public function testPurchase()
	{
		$response = $this->gateway->purchase(array(
				'amount' 	=> '10.00',
				'currency'	=> 'USD',
				'card'		=> array(
					'number' 		=> '4000000000000010',
					'expiryMonth' 	=> '09',
					'expiryYear'	=> '2019',
					'cvv'			=> '123'
				)
			));

		$this->assertTrue($response->isSuccessful());
		$this->assertFalse($response->isRedirect());
	}
}