<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Stripe;

use Tala\AbstractGateway;
use Tala\Exception\UnsupportedOperationException;
use Tala\Request;

/**
 * Stripe Gateway
 *
 * @link https://stripe.com/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://api.stripe.com/v1';

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
        );
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildPurchase($request, $source);

        return $this->send('/charges', $data);
    }

    public function refund(Request $request)
    {
        $request->validateRequired(array('gatewayReference', 'amount'));
        $data = array('amount' => $request->amount);

        return $this->send('/charges/'.$request->gatewayReference.'/refund', $data);
    }

    protected function buildPurchase(Request $request, $source)
    {
        $data = array();
        $data['amount'] = $request->amount;
        $data['card'] = $source;
        $data['currency'] = strtolower($request->currency);
        $data['description'] = $request->description;

        return $data;
    }

    protected function send($url, $data)
    {
        $response = $this->getHttpClient()->post($this->endpoint.$url, $data);

        return new Response($response);
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(Request $request, $source)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function completeAuthorize(Request $request)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function capture(Request $request)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function completePurchase(Request $request)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function void(Request $request)
    {
        throw new UnsupportedOperationException();
    }
}
