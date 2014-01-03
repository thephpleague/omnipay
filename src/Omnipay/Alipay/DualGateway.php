<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午12:53
 *
 */
namespace Omnipay\Alipay;

/**
 * Class DualGateway
 *
 * @package Omnipay\Alipay
 */
class DualGateway extends SecuredGateway
{

    protected $service_name = 'trade_create_by_buyer';

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'AliPay Dual Func';
    }

    public function purchase(array $parameters = array())
    {
        $this->setService($this->service_name);
        return $this->createRequest('\Omnipay\Alipay\Message\SecuredPurchaseRequest', $parameters);
    }
}
