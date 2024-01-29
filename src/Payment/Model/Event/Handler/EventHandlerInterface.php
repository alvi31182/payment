<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Handler;

use React\Promise\Deferred;

interface EventHandlerInterface
{
    public function handle(Deferred $deferred);
}