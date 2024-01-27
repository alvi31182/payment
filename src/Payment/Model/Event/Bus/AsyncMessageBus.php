<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Bus;

use React\Promise\Deferred;

class AsyncMessageBus implements AsyncMessageBusInterface
{
    public function bus(Deferred $deferred): void
    {
       dd($deferred);
    }
}