<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

interface ReadAccountStorage
{
    /**
     * @param Email $email
     *
     * @return iterable
     */
    public function getAccountData(Email $email): iterable;
}