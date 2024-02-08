<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

interface UserWriteStorage
{
    public function save(Account $user): void;
}