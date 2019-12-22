<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use Framework\ComandConfigs;
use Framework\ComandRoutes;
use Framework\ComandProcess;
use Framework\ReceiverConfig;
use Framework\ReceiverRoutes;
use Framework\ReceiverProcess;

class Kernel
{
    /**
     * @var RouteCollection
     */
    protected $routeCollection;

    /**
     * @var ContainerBuilder
     */
    protected $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    /**
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $configs = new ComandConfigs(new ReceiverConfig($this->containerBuilder));
        $this->containerBuilder = $configs->execute();
        $route = new ComandRoutes(new ReceiverRoutes($this->containerBuilder));
        $this->routeCollection = $route->execute();;
        $process = new ComandProcess($request, new ReceiverProcess($this->routeCollection));
        return $process->execute();
    }
}


