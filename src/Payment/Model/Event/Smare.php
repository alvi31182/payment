<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use React\Promise\Deferred;

#[AsyncEventHandler]
class Smare
{
    public function __invoke(Deferred $deferred)
    {
        dd($deferred);
        // TODO: Implement __invoke() method.
    }
}