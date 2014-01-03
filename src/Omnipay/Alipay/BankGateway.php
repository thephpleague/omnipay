<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午1:00
 *
 */
namespace Omnipay\Alipay;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class BankGateway
 *
 * @package Omnipay\Alipay
 */
class BankGateway extends ExpressGateway
{

    protected $service_name = 'create_direct_pay_by_user';

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Alipay Bank';
    }

    public function purchase(array $parameters = array())
    {
        $this->setService($this->service_name);
        $this->setParameter('paymethod', 'bankPay');
        if ($this->getParameter('defaultbank') === null) {
            throw new InvalidRequestException("The setDefaultBank(x) method is not called.");
        }
        return $this->createRequest('\Omnipay\Alipay\Message\ExpressPurchaseRequest', $parameters);
    }
}
