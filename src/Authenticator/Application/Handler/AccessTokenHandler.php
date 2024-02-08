<?php

declare(strict_types=1);

namespace App\Authenticator\Application\Handler;

use App\Authenticator\Domain\Email;
use App\Authenticator\Domain\ReadAccountStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{

    public function __construct(
        private ReadAccountStorage $readAccountStorage
    )
    {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $e = $this->readAccountStorage->getAccountData(new Email($accessToken));

        return new UserBadge($e->email);
    }
}