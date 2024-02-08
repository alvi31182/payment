<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

interface UserReadStorage
{
    public function isExistsUser(UserId $userId): bool;
}