<?
namespace Omnipay\PayU;

use Omnipay\Common\AbstractGateway;


class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayU';
    }

    public function getMerchant()
    {
        return $this->getParameter('MERCHANT');
    }

    public function setMerchant($value)
    {
        return $this->setParameter('MERCHANT', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('SECRET_KEY');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('SECRET_KEY', $value);
    }

    public function getDefaultParameters()
    {
        return array(
            'MERCHANT' => '',
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayU\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayU\Message\PurchaseRequest', $parameters);
    }
}