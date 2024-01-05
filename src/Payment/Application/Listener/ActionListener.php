<?php

declare(strict_types=1);

namespace App\Payment\Application\Listener;

use App\Payment\Application\UseCase\Authorization\Authorize;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class ActionListener
{
    public function __construct(
        private Authorize $authorize,
        private string $secretKey
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {

        $request = $event->getRequest();

        $token = $request->headers->get('authorization');

        if ($token === null) {
            return;
        }

        $this->authorize->tokenValidator(token: $token, secretKey: $this->secretKey);
    }
}
