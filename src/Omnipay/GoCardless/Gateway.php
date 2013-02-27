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
use Omnipay\GoCardless\Message\PurchaseRequest;
use Omnipay\GoCardless\Message\CompletePurchaseRequest;

/**
 * GoCardless Gateway
 *
 * @link https://sandbox.gocardless.com/docs
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'GoCardless';
    }

    public function getDefaultParameters()
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
        return $this->getParameter('appId');
    }

    public function setAppId($value)
    {
        return $this->setParameter('appId', $value);
    }

    public function getAppSecret()
    {
        return $this->getParameter('appSecret');
    }

    public function setAppSecret($value)
    {
        return $this->setParameter('appSecret', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GoCardless\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GoCardless\Message\CompletePurchaseRequest', $parameters);
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
}
