<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PagSeguro\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\PagSeguro\Message\Service\PaymentService;

/**
 * PagSeguro Abstract Request
 */
abstract class AbstractRequest extends BaseRequest
{
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getCurrency()
    {
        // return 'BRL';
        return $this->getParameter('currency');
    }

    public function getCharset()
    {
        return 'UTF-8';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function send()
    {
        $data     = $this->getData();
        $service  = new PaymentService($data['credentials']);
        $response = $service->send($data['paymentRequest']);

        return new Response($this, $response);
    }
}
