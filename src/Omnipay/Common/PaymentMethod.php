<?php

namespace Omnipay\Common;

/**
 * Payment Method
 */
class PaymentMethod
{
    protected $id;
    protected $name;

    /**
     * Create a new PaymentMethod
     *
     * @param string $id   The identifier of this payment method
     * @param string $name The name of this payment method
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * The identifier of this payment method
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The name of this payment method
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
