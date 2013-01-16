<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\GoCardless;

use Tala\AbstractGateway;
use Tala\Exception\InvalidResponseException;
use Tala\RedirectResponse;
use Tala\Request;

/**
 * GoCardless Gateway
 *
 * @link https://sandbox.gocardless.com/docs
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://gocardless.com';
    protected $testEndpoint = 'https://sandbox.gocardless.com';

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'merchantId' => '',
            'accessToken' => '',
            'testMode' => false,
        );
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildPurchase($request, $source);

        return new RedirectResponse($this->getCurrentEndpoint().'/connect/bills/new?'.
            $this->generateQueryString($data));
    }

    public function completePurchase(Request $request)
    {
        $data = array();
        $data['resource_uri'] = $this->getHttpRequest()->get('resource_uri');
        $data['resource_id'] = $this->getHttpRequest()->get('resource_id');
        $data['resource_type'] = $this->getHttpRequest()->get('resource_type');

        if ($this->generateSignature($data) !== $this->getHttpRequest()->get('signature')) {
            throw new InvalidResponseException;
        }

        unset($data['resource_uri']);

        // confirm purchase
        $headers = array(
            'Authorization: Basic '.base64_encode($this->username.':'.$this->password),
            'Accept: application/json',
        );

        $response = $this->getHttpClient()->post(
            $this->getCurrentEndpoint().'/api/v1/confirm', $this->generateQueryString($data), $headers);

        return new Response($response, $data['resource_id']);
    }

    protected function buildPurchase(Request $request, $source)
    {
        $request->validateRequired(array('amount', 'returnUrl'));

        $data = array();
        $data['client_id'] = $this->username;
        $data['nonce'] = $this->generateNonce();
        $data['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        $data['redirect_uri'] = $request->returnUrl;
        $data['cancel_uri'] = $request->cancelUrl;
        $data['bill'] = array();
        $data['bill']['merchant_id'] = $this->merchantId;
        $data['bill']['amount'] = $request->amountDollars;
        $data['bill']['name'] = $request->description;
        $data['bill']['user'] = array();
        $data['bill']['user']['first_name'] = $source->firstName;
        $data['bill']['user']['last_name'] = $source->lastName;
        $data['bill']['user']['email'] = $source->email;
        $data['bill']['user']['billing_address1'] = $source->address1;
        $data['bill']['user']['billing_address2'] = $source->address2;
        $data['bill']['user']['billing_town'] = $source->city;
        $data['bill']['user']['billing_county'] = $source->country;
        $data['bill']['user']['billing_postcode'] = $source->postcode;

        $data['signature'] = $this->generateSignature($data);

        return $data;
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }

    /**
     * Generate a nonce for each request
     */
    protected function generateNonce()
    {
        $nonce = '';
        for ($i = 0; $i < 64; $i++) {
            // append random ASCII character
            $nonce .= chr(mt_rand(33, 126));
        }

        return base64_encode($nonce);
    }

    /**
     * Generate a signature for the data array
     */
    protected function generateSignature($data)
    {
        return hash_hmac('sha256', $this->generateQueryString($data), $this->password);
    }

    /**
     * Generate a query string for the data array (seriously?)
     */
    protected function generateQueryString($params, &$pairs = array(), $namespace = null)
    {
        if (is_array($params)) {
            foreach ($params as $k => $v) {
                if (is_int($k)) {
                    $this->generateQueryString($v, $pairs, $namespace.'[]');
                } else {
                    $this->generateQueryString($v, $pairs, $namespace !== null ? $namespace."[$k]" : $k);
                }
            }

            if ($namespace !== null) {
                return $pairs;
            }

            if (empty($pairs)) {
                return '';
            }

            sort($pairs);
            $strs = array_map('implode', array_fill(0, count($pairs), '='), $pairs);

            return implode('&', $strs);
        } else {
            $pairs[] = array(rawurlencode($namespace), rawurlencode($params));
        }
    }
}
