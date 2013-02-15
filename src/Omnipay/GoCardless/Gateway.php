<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\RedirectResponse;
use Omnipay\Common\Request;

/**
 * GoCardless Gateway
 *
 * @link https://sandbox.gocardless.com/docs
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://gocardless.com';
    protected $testEndpoint = 'https://sandbox.gocardless.com';
    protected $appId;
    protected $appSecret;
    protected $merchantId;
    protected $accessToken;
    protected $testMode;

    public function getName()
    {
        return 'GoCardless';
    }

    public function defineSettings()
    {
        return array(
            'appId' => '',
            'appSecret' => '',
            'merchantId' => '',
            'accessToken' => '',
            'testMode' => false,
        );
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function setAppId($value)
    {
        $this->appId = $value;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function setAppSecret($value)
    {
        $this->appSecret = $value;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($value)
    {
        $this->accessToken = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function purchase($options)
    {
        $data = $this->buildPurchase($options);

        return new RedirectResponse(
            $this->getCurrentEndpoint().'/connect/bills/new?'.$this->generateQueryString($data)
        );
    }

    public function completePurchase($options)
    {
        $data = array();
        $data['resource_uri'] = $this->httpRequest->get('resource_uri');
        $data['resource_id'] = $this->httpRequest->get('resource_id');
        $data['resource_type'] = $this->httpRequest->get('resource_type');

        if ($this->generateSignature($data) !== $this->httpRequest->get('signature')) {
            throw new InvalidResponseException;
        }

        unset($data['resource_uri']);

        // confirm purchase
        $httpResponse = $this->httpClient->post(
            $this->getCurrentEndpoint().'/api/v1/confirm',
            array('Accept' => 'application/json'),
            $this->generateQueryString($data)
        )->setAuth($this->appId, $this->appSecret)->send();

        return new Response($httpResponse->getBody(), $data['resource_id']);
    }

    protected function buildPurchase($options)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'returnUrl'));
        $source = $request->getCard();

        $data = array();
        $data['client_id'] = $this->appId;
        $data['nonce'] = $this->generateNonce();
        $data['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        $data['redirect_uri'] = $request->getReturnUrl();
        $data['cancel_uri'] = $request->getCancelUrl();
        $data['bill'] = array();
        $data['bill']['merchant_id'] = $this->merchantId;
        $data['bill']['amount'] = $request->getAmountDecimal();
        $data['bill']['name'] = $request->getDescription();

        if ($source) {
            $data['bill']['user'] = array();
            $data['bill']['user']['first_name'] = $source->getFirstName();
            $data['bill']['user']['last_name'] = $source->getLastName();
            $data['bill']['user']['email'] = $source->getEmail();
            $data['bill']['user']['billing_address1'] = $source->getAddress1();
            $data['bill']['user']['billing_address2'] = $source->getAddress2();
            $data['bill']['user']['billing_town'] = $source->getCity();
            $data['bill']['user']['billing_county'] = $source->getCountry();
            $data['bill']['user']['billing_postcode'] = $source->getPostcode();
        }

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
        return hash_hmac('sha256', $this->generateQueryString($data), $this->appSecret);
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
