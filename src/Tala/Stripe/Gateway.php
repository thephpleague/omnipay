<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Stripe;

use Tala\AbstractGateway;
use Tala\Request;

/**
 * Stripe Gateway
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://api.stripe.com/v1';

    public function getDefaultSettings()
    {
        return array(
            'api_key' => '',
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
        $response = $this->getBrowser()->post($this->endpoint.$url, array(), $data);

        return new Response($response->getContent());
    }
}
