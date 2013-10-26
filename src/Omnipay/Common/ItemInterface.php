<?php

namespace Omnipay\Common;

/**
 * Cart Item interface
 */
interface ItemInterface
{
    /**
     * Name of the item
     */
    public function getName();

    /**
     * Description of the item
     */
    public function getDescription();

    /**
     * Quantity of the item
     */
    public function getQuantity();

    /**
     * Price of the item
     */
    public function getPrice();
}
