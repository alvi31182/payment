<?php

declare(strict_types=1);

namespace App\Payment\Application\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $message = $throwable->getMessage();

        $response = new JsonResponse([
            'message' => $message,
            'trace' => $throwable->getTraceAsString(),
        ]);

        $event->setResponse($response);
    }
}
