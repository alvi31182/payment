<?php

declare(strict_types=1);

namespace App\Payment\Application\Listener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    public function __invoke(ExceptionEvent $event)
    {
        // TODO: Implement __invoke() method.
    }
}
