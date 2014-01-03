<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-3 上午12:52
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Tests\TestCase;

class ExpressCompletePurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        $this->request = new ExpressCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                 'request_params' => array(
                     'notify_id'    => '10010',
                     'sign'         => 'the_sign_return_from_alipay',
                     'trade_status' => 'TRADE_SUCCESS',
                     'out_trade_no' => '2014010202010001',
                     'trade_no'     => '20140102020100019853',
                 ),
                 'transport'      => 'http',
                 'partner'        => '123456789',
                 'ca_cert_path'   => dirname(__DIR__) . '/Mock/cacert.pem',
                 'sign_type'      => 'MD5',
                 'key'            => 'here_is_key',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('10010', $data['request_params']['notify_id']);
        $this->assertSame('the_sign_return_from_alipay', $data['request_params']['sign']);
        $this->assertSame('TRADE_SUCCESS', $data['request_params']['trade_status']);
        $this->assertSame('123456789', $data['partner']);
    }
}
 