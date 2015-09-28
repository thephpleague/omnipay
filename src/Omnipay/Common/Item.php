<?php
/**
 * Cart Item
 */

namespace Omnipay\Common;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Cart Item
 *
 * This class defines a single cart item in the Omnipay system.
 *
 * @see ItemInterface
 */
class Item implements ItemInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * Create a new item with the specified parameters
     *
     * @param array|null $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize this item with the specified parameters
     *
     * @param array|null $parameters An array of parameters to set on this object
     * @return $this Item
     */
    public function initialize($parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters->all();
    }

    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the item name
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Set the item description
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getQuantity()
    {
        return $this->getParameter('quantity');
    }

    /**
     * Set the item quantity
     */
    public function setQuantity($value)
    {
        return $this->setParameter('quantity', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrice()
    {
        return $this->getParameter('price');
    }

    /**
     * Set the item price
     */
    public function setPrice($value)
    {
        return $this->setParameter('price', $value);
    }
}
