<?php

namespace League\Omnipay\Container;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

class ServerRequestServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ServerRequestInterface::class,
    ];

    public function register()
    {
        $this->getContainer()->add(ServerRequestInterface::class, function () {
            return ServerRequestFactory::fromGlobals();
        }, true);
    }
}
