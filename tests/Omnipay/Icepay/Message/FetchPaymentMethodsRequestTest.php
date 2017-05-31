<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    /**
     * @var FetchPaymentMethodsRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantId' => '111111',
            'secretCode' => '222222',
        ));
    }

    /**
     * @dataProvider paymentMethodsProvider
     */
    public function testSendSuccess($expected)
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expected, $response->getPaymentMethods());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals("ERR_0006: MerchantID '666' was not found", $response->getMessage());
        $this->assertEquals('s:Client', $response->getCode());
    }

    public function paymentMethodsProvider()
    {
        return array(
            array(
                array(
                    'DDEBIT' => array(
                        'Description' => 'Direct Debit',
                        'Issuers' => array(
                            'IDEALINCASSO' => array(
                                'Description' => 'Automatic incasso Ideal',
                                'Countries' => array(
                                    'NL' => array(
                                        'Currency' => 'EUR',
                                        'MaximumAmount' => '200000',
                                        'MinimumAmount' => '30',
                                    ),
                                ),
                            ),
                            'INCASSO' => array(
                                'Description' => 'Automatische incasso',
                                'Countries' => array(
                                    'NL' => array(
                                        'Currency' => 'EUR',
                                        'MaximumAmount' => '200000',
                                        'MinimumAmount' => '30',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'DIRECTEBANK' => array(
                        'Description' => 'Direct e-Banking',
                        'Issuers' => array(
                            'RETAIL' => array(
                                'Description' => 'Direct e-banking Retail',
                                'Countries' => array(
                                    'AT' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'BE' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'CH' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'DE' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'ES' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'FR' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'GB' => array(
                                        'Currency' => 'EUR, EUR, CHF, CHF, GBP, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                    'IT' => array(
                                        'Currency' => 'EUR, CHF, GBP',
                                        'MaximumAmount' => '1000001',
                                        'MinimumAmount' => '30',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}
