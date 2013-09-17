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
use Guzzle\Http\Client;
use Guzzle\Common\Event;

/**
 * PagSeguro Abstract Request
 */
abstract class AbstractRequest extends BaseRequest
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://ws.pagseguro.uol.com.br/v2/checkout';

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
        return 'BRL';
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

    public function getData()
    {
        $data = array();
        $data['email'] = $this->getEmail();
        $data['token'] = $this->getToken();
        $data['currency'] = $this->getCurrency();
        $data['charset']  = $this->getCharset();

        return $data;
    }

    public function send()
    {
        // $request = new HttpClient();
        // $request->post($this->getEndpoint(), $this->getData());

        $options = array(
            'curl.options' => array(
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
                )
            )
        );

        var_dump($this->getData());
        die;

        $request = $this->httpClient->createRequest(
            'POST',
            $this->endpoint,
            null,
            http_build_query($this->getData(), '', '&'),
            $options
        );

        var_dump($request);
        die;

        $request->send();
        var_dump($request);
        // $request->send();

        // return $this->response = new Response($this, $response->getBody());
    }

}
