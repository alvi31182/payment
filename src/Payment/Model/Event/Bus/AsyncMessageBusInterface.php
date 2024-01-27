<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Bus;

use React\Promise\Deferred;

interface AsyncMessageBusInterface
{
    public function bus(Deferred $deferred): void;
}