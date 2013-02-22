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
use Omnipay\Common\Message\RequestInterface;
use Omnipay\GoCardless\Message\PurchaseRequest;
use Omnipay\GoCardless\Message\CompletePurchaseRequest;

/**
 * GoCardless Gateway
 *
 * @link https://sandbox.gocardless.com/docs
 */
class Gateway extends AbstractGateway
{
    protected $liveEndpoint = 'https://gocardless.com';
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

        return $this;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function setAppSecret($value)
    {
        $this->appSecret = $value;

        return $this;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;

        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($value)
    {
        $this->accessToken = $value;

        return $this;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;

        return $this;
    }

    public function purchase($options = null)
    {
        $request = new PurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completePurchase($options = null)
    {
        $request = new CompletePurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function send(RequestInterface $request)
    {
        if ($request instanceof PurchaseRequest) {
            return $this->createResponse($request, $request->getData())->setEndpoint($this->getEndpoint());
        }

        $httpResponse = $this->httpClient->post(
            $this->getEndpoint().'/api/v1/confirm',
            array('Accept' => 'application/json'),
            static::generateQueryString($request->getData())
        )->setAuth($this->appId, $this->appSecret)->send();

        return $this->createResponse($request, $httpResponse->json());
    }

    /**
     * Generate a query string for the data array (this is some kind of sick joke)
     *
     * @link https://github.com/gocardless/gocardless-php/blob/v0.3.3/lib/GoCardless/Utils.php#L39
     */
    public static function generateQueryString($data, &$pairs = array(), $namespace = null)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_int($k)) {
                    static::generateQueryString($v, $pairs, $namespace.'[]');
                } else {
                    static::generateQueryString($v, $pairs, $namespace !== null ? $namespace."[$k]" : $k);
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
            $pairs[] = array(rawurlencode($namespace), rawurlencode($data));
        }
    }

    protected function getEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }
}
