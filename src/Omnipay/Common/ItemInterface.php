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
     * SKU of the item
     */
    public function getSku();

    /**
     * Quantity of the item
     */
    public function getQuantity();

    /**
     * Price of the item
     */
    public function getPrice();
}
