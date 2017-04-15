<?php
namespace Omnipay\PagSeguro\Message\ValueObject;

class Shipping
{
    /**
     * @var int
     */
    const TYPE_PAC = 1;

    /**
     * @var int
     */
    const TYPE_SEDEX = 2;

    /**
     * @var int
     */
    const TYPE_UNKNOWN = 3;

    /**
     * @var int
     */
    private $type;

    /**
     * @var \Omnipay\PagSeguro\Message\ValueObject\Address
     */
    private $address;

    /**
     * @var float
     */
    private $cost;

    /**
     * @param int $type
     * @param \Omnipay\PagSeguro\Message\ValueObject\Address $address
     * @param float $cost
     */
    public function __construct($type, Address $address = null, $cost = null)
    {
        $this->setType($type);
        $this->setAddress($address);

        if ($cost !== null) {
            $this->setCost($cost);
        }
    }

    /**
     * @return number
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param number $type
     */
    protected function setType($type)
    {
        $this->type = (int) $type;
    }

    /**
     * @return \Omnipay\PagSeguro\Message\ValueObject\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Address $address
     */
    protected function setAddress(Address $address = null)
    {
        $this->address = $address;
    }

    /**
     * @return number
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param number $cost
     */
    protected function setCost($cost)
    {
        $this->cost = (float) $cost;
    }
}
