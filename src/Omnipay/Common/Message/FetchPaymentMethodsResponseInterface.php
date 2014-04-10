<?php

namespace Omnipay\Common\Message;

/**
 * Fetch Payment Methods Response interface
 */
interface FetchPaymentMethodsResponseInterface extends ResponseInterface
{
    /**
     * Get the returned list of payment methods.
     *
     * These represent separate payment methods which the user must choose between.
     *
     * @return array An array of \Omnipay\Common\PaymentMethod objects
     */
    public function getPaymentMethods();
}
