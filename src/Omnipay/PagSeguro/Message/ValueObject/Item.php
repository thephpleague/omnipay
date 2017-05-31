<?php

namespace Omnipay\PagSeguro\Message\ValueObject;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Item
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $shippingCost;

    /**
     * @var int
     */
    private $weight;

    /**
     * @param string $id
     * @param string $description
     * @param float $amount
     * @param int $quantity
     * @param float $shippingCost
     * @param int $weight
     */
    public function __construct(
        $id,
        $description,
        $amount,
        $quantity = 1,
        $shippingCost = null,
        $weight = null
    ) {
        $this->setId($id);
        $this->setDescription($description);
        $this->setAmount($amount);
        $this->setQuantity($quantity);

        if ($shippingCost !== null) {
            $this->setShippingCost($shippingCost);
        }

        if ($weight !== null) {
            $this->setWeight($weight);
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    protected function setId($id)
    {
        $this->id = substr((string) $id, 0, 100);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    protected function setDescription($description)
    {
        $this->description = substr((string) $description, 0, 100);
    }

    /**
     * @return number
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param number $amount
     */
    protected function setAmount($amount)
    {
        $this->amount = round((float) $amount, 2);
    }

    /**
     * @return number
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param number $quantity
     */
    protected function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;
    }

    /**
     * @return number
     */
    public function getShippingCost()
    {
        return $this->shippingCost;
    }

    /**
     * @param number $shippingCost
     */
    protected function setShippingCost($shippingCost)
    {
        $this->shippingCost = round((float) $shippingCost, 2);
    }

    /**
     * @return number
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param number $weight
     */
    protected function setWeight($weight)
    {
        $this->weight = (int) $weight;
    }
}
