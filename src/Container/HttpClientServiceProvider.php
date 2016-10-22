<?php

namespace League\Omnipay\Container;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Omnipay\Common\Http\ClientInterface;
use League\Omnipay\Common\Http\GuzzleClient;

class HttpClientServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ClientInterface::class,
    ];

    public function register()
    {
        $this->getContainer()->add(ClientInterface::class, GuzzleClient::class);
    }
}
