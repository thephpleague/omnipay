<?php
/**
 * Cart Item
 */

namespace Omnipay\Common;

/**
 * Cart Item
 *
 * This class defines a single cart item in the Omnipay system.
 *
 * @see ItemInterface
 */
class Item implements ItemInterface, ParameterizedInterface
{
    use HasParametersTrait;

    /**
     * Create a new item with the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct(array $parameters = [])
    {
        $this->initialize($parameters);
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
