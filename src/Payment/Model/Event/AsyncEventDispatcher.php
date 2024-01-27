<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use React\EventLoop\LoopInterface;
use React\Promise\Deferred;

class AsyncEventDispatcher implements AsyncEventDispatcherInterface
{
    private array $listeners = [];
    public function __construct(
        private readonly LoopInterface $loop,
        private AsyncListenerInterface $listener
    ) {
    }

    public function dispatch(object $event): void
    {
        $deferred = new Deferred();
        $deferred->resolve($event);

        $this->loop->futureTick(function () use ($deferred): void {
            $this->executeListeners($deferred);
        });

        $this->loop->run();
        $this->loop->stop();
    }


    private function executeListeners(Deferred $deferred): void
    {
        $this->listener->publish($deferred->promise());
    }
}