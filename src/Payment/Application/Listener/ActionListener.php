<?php

declare(strict_types=1);

namespace App\Payment\Application\Listener;

use App\Payment\Application\Exception\InvalidTokenException;
use App\Payment\Application\Exception\UnauthorizedException;
use App\Payment\Application\UseCase\Authorization\Authorize;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class ActionListener
{
    public function __construct(
        private Authorize $authorize,
        private string $secretKey
    ) {
    }

    /**
     * @throws UnauthorizedException
     * @throws InvalidTokenException
     */
    public function __invoke(RequestEvent $event): void
    {

        $request = $event->getRequest();

        $token = $request->headers->get('Authorizations');

        if ($token === null) {
            return;
//            throw new UnauthorizedException(
//                'Unauthorized'
//            );
        }

        $this->authorize->tokenValidator(token: $token, secretKey: $this->secretKey);
    }
}
