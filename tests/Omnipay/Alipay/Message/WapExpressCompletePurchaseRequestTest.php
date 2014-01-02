<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-3 上午12:52
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Tests\TestCase;

class WapExpressCompletePurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        $this->request = new WapExpressCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                 'request_params' => array(
                     'notify_data' => '<xml></xml>',
                     'trade_status' => 'TRADE_SUCCESS',
                 ),
                 'private_key'    => 'the_own_private_key',
                 'partner'        => '451235632',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('<xml></xml>', $data['request_params']['notify_data']);
        $this->assertSame('TRADE_SUCCESS', $data['request_params']['trade_status']);
        $this->assertSame('the_own_private_key', $data['private_key']);
        $this->assertSame('451235632', $data['partner']);
    }
}
 