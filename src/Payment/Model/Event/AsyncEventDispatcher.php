<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use App\Payment\Model\Event\Bus\AsyncMessageBusInterface;
use App\Payment\Model\Event\Handler\EventHandlerInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;

class AsyncEventDispatcher implements AsyncEventDispatcherInterface
{
    private array $listeners = [];
    public function __construct(
        private readonly LoopInterface $loop,
        private readonly AsyncMessageBusInterface $bus
    ) {
    }

    public function subscribe(EventHandlerInterface $subscriber, string $eventName): void
    {
        if (!isset($this->subscribers[$eventName])) {
            $this->subscribers[$eventName] = [];
        }

        $this->subscribers[$eventName][] = $subscriber;
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
        $this->bus->bus(deferred: $deferred);
    }
}