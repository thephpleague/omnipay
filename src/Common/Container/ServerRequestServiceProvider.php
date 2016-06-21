<?php

namespace League\Omnipay\Common\Container;

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
            ServerRequestFactory::fromGlobals();
        }, true);
    }
}
