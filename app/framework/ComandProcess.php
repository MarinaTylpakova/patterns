<?php


namespace Framework;

use Symfony\Component\HttpFoundation\Request;

class ComandProcess implements ICommand
{
    /**
     * @var ReceiverProcess
     */
    private $receiver;
    public $Response;
    /**
     * @var Request
     */
    private $request;


    public function __construct(Request $request, ReceiverProcess $receiver)
    {
        $this->receiver = $receiver;
        $this->request = $request;
    }

    public function execute()
    {
        return $this->receiver->process($this->request);
    }
}