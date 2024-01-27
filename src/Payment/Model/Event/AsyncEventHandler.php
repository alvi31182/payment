<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use App\EventStorage\Model\WriteEventStorage;
use Attribute;
use React\Promise\PromiseInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class AsyncEventHandler implements AsyncListenerInterface
{
    public function publish(PromiseInterface $promise): PromiseInterface
    {
        return $promise;
    }
}