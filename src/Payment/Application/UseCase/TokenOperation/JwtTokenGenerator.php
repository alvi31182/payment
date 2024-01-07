<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\TokenOperation;

use Firebase\JWT\JWT;

class JwtTokenGenerator
{
    private const ALGORITHM_HS256 = 'HS256';

    public function generateToken(array $data, string $secretKey, int $expirationTimeInSeconds): string
    {
        return JWT::encode(
            [
                'playerId' => $data,
                'exp' => time() + $expirationTimeInSeconds,
            ],
            $secretKey,
            self::ALGORITHM_HS256
        );
    }
}
