<?php

namespace Omnipay\PagSeguro\Message\Service;

use Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentResponse;
use Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest;
use Omnipay\PagSeguro\Message\ValueObject\Credentials;
use Omnipay\PagSeguro\Message\Codec\PaymentEncoder;
use Omnipay\PagSeguro\Message\Codec\PaymentDecoder;
use Omnipay\PagSeguro\Message\Http\Client;

class PaymentService
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://ws.pagseguro.uol.com.br/v2/checkout';

    /**
     * @var \Omnipay\PagSeguro\Message\ValueObject\Credentials
     */
    private $credentials;

    /**
     * @var \Omnipay\PagSeguro\Message\Http\Client
     */
    private $client;

    /**
     * @var \Omnipay\PagSeguro\Message\Codec\PaymentEncoder
     */
    private $encoder;

    /**
     * @var \Omnipay\PagSeguro\Message\Codec\PaymentDecoder
     */
    private $decoder;

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Credentials $credentials
     * @param \Omnipay\PagSeguro\Message\Http\Client $client
     * @param \Omnipay\PagSeguro\Message\Codec\PaymentEncoder $encoder
     * @param \Omnipay\PagSeguro\Message\Codec\PaymentDecoder $decoder
     */
    public function __construct(
        Credentials $credentials,
        Client $client = null,
        PaymentEncoder $encoder = null,
        PaymentDecoder $decoder = null
    ) {
        $this->credentials = $credentials;
        $this->client = $client ?: new Client();
        $this->encoder = $encoder ?: new PaymentEncoder();
        $this->decoder = $decoder ?: new PaymentDecoder();
    }

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     * @return \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentResponse
     */
    public function send(PaymentRequest $request)
    {
        $content = $this->client->post(
            static::ENDPOINT,
            $this->encoder->encode($this->credentials, $request)
        );

        return $this->decoder->decode($content);
    }
}
