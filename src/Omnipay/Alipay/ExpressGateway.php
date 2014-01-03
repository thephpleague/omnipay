<?php

namespace Omnipay\Alipay;

/**
 * Class ExpressGateway
 *
 * @package Omnipay\Alipay
 */
class ExpressGateway extends BaseAbstractGateway
{

    protected $service_name = 'create_direct_pay_by_user';

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Alipay Express';
    }

    public function purchase(array $parameters = array())
    {
        $this->setService($this->service_name);
        return $this->createRequest('\Omnipay\Alipay\Message\ExpressPurchaseRequest', $parameters);
    }
}
