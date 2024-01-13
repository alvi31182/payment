<?php

declare(strict_types=1);

namespace App\Notify\Phone;

readonly class PhoneNotify
{
    public function __construct(
        public string $phoneNumber
    ) {
    }
}
