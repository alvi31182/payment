<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use DateTimeImmutable;
use Psr\EventDispatcher\EventDispatcherInterface;

interface AsyncEventDispatcherInterface
{
    /**
     * @param object{occuredOn: DateTimeImmutable} $event
     */
    public function dispatch(object $event): void;
}