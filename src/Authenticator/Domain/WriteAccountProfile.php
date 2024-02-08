<?php

namespace App\Authenticator\Domain;

interface WriteAccountProfile
{
    public function save(AccountProfile $accountProfile): void;
}