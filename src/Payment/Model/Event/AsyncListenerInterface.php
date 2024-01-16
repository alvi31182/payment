<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

interface AsyncListenerInterface
{
    public function handle(Deferred $deferred): PromiseInterface;
}