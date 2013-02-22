<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless\Message;

use Omnipay\GoCardless\Gateway;

/**
 * GoCardless Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $appId;
    protected $appSecret;
    protected $merchantId;
    protected $accessToken;
    protected $testMode;

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

    /**
     * Generate a signature for the data array
     */
    public function generateSignature($data)
    {
        return hash_hmac('sha256', Gateway::generateQueryString($data), $this->appSecret);
    }
}
