<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept;

use Omnipay\Common\AbstractGateway;
use Omnipay\Exception;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Netaxept\Message\PurchaseRequest;
use Omnipay\Netaxept\Message\CompletePurchaseRequest;

/**
 * Netaxept Gateway
 *
 * @link http://www.betalingsterminal.no/Netthandel-forside/Teknisk-veiledning/Overview/
 */
class Gateway extends AbstractGateway
{
    protected $liveEndpoint = 'https://epayment.bbs.no';
    protected $testEndpoint = 'https://epayment-test.bbs.no';
    protected $merchantId;
    protected $token;
    protected $testMode;

    public function getName()
    {
        return 'Netaxept';
    }

    public function defineSettings()
    {
        return array(
            'merchantId' => '',
            'token' => '',
            'testMode' => false,
        );
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

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;

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
        $url = $this->getEndpoint();
        $data = $request->getData();

        if ($request instanceof CompletePurchaseRequest) {
            $url .= '/Netaxept/Process.aspx';

            if ('OK' !== $data['responseCode']) {
                return $this->createResponse($request, $data);
            }
        } else {
            $url .= '/Netaxept/Register.aspx';
        }

        $httpResponse = $this->httpClient->get($url.'?'.http_build_query($request->getData()))->send();

        return $this->createResponse($request, $httpResponse->xml());
    }

    public function getEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }
}
