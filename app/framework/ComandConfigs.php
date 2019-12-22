<?php


namespace Framework;

class ComandConfigs implements ICommand
{
    /**
    * @var ReceiverConfig
    */
    private $receiver;


    public function __construct(ReceiverConfig $receiver)
    {
        $this->receiver = $receiver;
    }

    public function execute()
    {
        return $this->receiver->registerConfigs();
    }

}