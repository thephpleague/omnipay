<?php
namespace League\Omnipay\Common\Container;

use Interop\Container\ContainerInterface as InteropContainerInterface;
use League\Container\Container;
use League\Container\ContainerInterface as LeagueContainerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class ContainerAliasServiceProvider
 * @package League\Omnipay\Common\Container
 */
class ContainerAliasServiceProvider extends AbstractServiceProvider
{

    /**
     * @var array
     */
    protected $provides = [
        InteropContainerInterface::class,
        LeagueContainerInterface::class,
        Container::class
    ];
    
    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->provides as $alias) {
            $this->getContainer()->add($alias, $this->getContainer());
        }
    }
}