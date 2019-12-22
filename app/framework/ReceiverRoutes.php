<?php


namespace Framework;


use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReceiverRoutes
{   /**
    * @var ContainerBuilder
    */
    private $containerBuilder;
    private $routeCollection;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    public function registerRoutes()
    {
        $this->routeCollection = require '../app/config/routing.php';
        $this->containerBuilder->set('route_collection', $this->routeCollection);
        return $this->routeCollection;
    }
}