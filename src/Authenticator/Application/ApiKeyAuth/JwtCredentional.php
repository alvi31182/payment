<?php

declare(strict_types=1);

namespace App\Authenticator\Application\ApiKeyAuth;

use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;

class JwtCredentional implements CredentialsInterface
{

    public function isResolved(): bool
    {
        // TODO: Implement isResolved() method.
    }
}