<?php

declare(strict_types=1);

namespace App\Authenticator\Application\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Registration',
    description: 'Player registration',
    required: ['email'],
)]
final class UserDataRegistration
{
    public function __construct(
        #[OA\Property(type: 'string', nullable: false)]
        public string $email
    ) {
    }
}