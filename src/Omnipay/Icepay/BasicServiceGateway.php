<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay;

use Omnipay\Icepay\Message\FetchPaymentMethodsRequest;

/**
 * Icepay web service basic integration.
 * Aka Version 2 basic integration.
 *
 * @link http://www.icepay.com/downloads/pdf/documentation/icepay_webservice.pdf
 */
class BasicServiceGateway extends Gateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Icepay BasicService';
    }

    /**
     * Retrieve payment methods active on the given Icepay
     * account.
     *
     * @param array $parameters
     *
     * @return FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Icepay\Message\FetchPaymentMethodsRequest', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Icepay\Message\BasicPurchaseRequest', $parameters);
    }
}
