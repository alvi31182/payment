<?php

declare(strict_types=1);

namespace App\Notify\Email;

readonly class EmailNotify
{
    public function __construct(
        public string $email
    ) {
    }
}
