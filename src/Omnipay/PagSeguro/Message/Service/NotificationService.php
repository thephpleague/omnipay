<?php

namespace Omnipay\PagSeguro\Message\Service;

use Omnipay\PagSeguro\Message\Codec\TransactionDecoder;
use Omnipay\PagSeguro\Message\ValueObject\Credentials;
use Omnipay\PagSeguro\Message\Http\Client;

class NotificationService
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications';

    /**
     * @var \Omnipay\PagSeguro\Message\ValueObject\Credentials
     */
    private $credentials;

    /**
     * @var \Omnipay\PagSeguro\Message\Http\Client
     */
    private $client;

    /**
     * @var \Omnipay\PagSeguro\Message\Codec\TransactionDecoder
     */
    private $decoder;

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Credentials $credentials
     * @param \Omnipay\PagSeguro\Message\Http\Client $client
     * @param \Omnipay\PagSeguro\Message\Codec\TransactionDecoder $decoder
     */
    public function __construct(
        Credentials $credentials,
        Client $client = null,
        TransactionDecoder $decoder = null
    ) {
        $this->credentials = $credentials;
        $this->client = $client ?: new Client();
        $this->decoder = $decoder ?: new TransactionDecoder();
    }

    /**
     * @param string $code
     * @return \Omnipay\PagSeguro\Message\ValueObject\Transaction
     */
    public function getByCode($code)
    {
        $content = $this->client->get(
            static::ENDPOINT . '/' . $code
            . '?email=' . $this->credentials->getEmail()
            . '&token=' . $this->credentials->getToken()
        );

        return $this->decoder->decode($content);
    }
}
