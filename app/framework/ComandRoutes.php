<?php


namespace Framework;


class ComandRoutes implements ICommand
{
    /**
     * @var ReceiverRoutes
     */
    private $receiver;
    public $routeCollection;


    public function __construct(ReceiverRoutes $receiver)
    {
        $this->receiver = $receiver;
    }

    public function execute()
    {
        return $this->receiver->registerRoutes();
    }
}