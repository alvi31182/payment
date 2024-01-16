<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use React\EventLoop\LoopInterface;
use React\Promise\Deferred;

readonly class AsyncEventDispatcher implements AsyncEventDispatcherInterface
{
    public function __construct(
        private LoopInterface $loop,
        private AsyncListenerInterface $listener
    ) {
    }

    public function dispatch(DomainEvent $domainEvent): void
    {
        $deferred = new Deferred();
        $deferred->resolve($domainEvent);

        $this->loop->futureTick(function () use ($deferred): void {
            $this->listener->handle($deferred);
        });

        $this->loop->run();
        $this->loop->stop();
    }
}