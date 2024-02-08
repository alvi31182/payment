<?php

declare(strict_types=1);

namespace App\Authenticator\Application\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Login for account',
    description: 'Player login',
    required: ['email','password'],
)]
class AccountLogin
{
   public function __construct(
       #[OA\Property(type: "string", format: "email")]
       public string $email,
       #[OA\Property(type: "string", format: "password")]
       public string $password
   )
   {
   }
}