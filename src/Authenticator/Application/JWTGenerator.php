<?php

declare(strict_types=1);

namespace App\Authenticator\Application;

interface JWTGenerator
{
    public function generate(string $data);
}