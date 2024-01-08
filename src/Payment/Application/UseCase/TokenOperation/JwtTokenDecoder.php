<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\TokenOperation;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final readonly class JwtTokenDecoder
{
    private const ALGORITHM_HS256 = 'HS256';

    public function decode(string $token, string $secretKey): TokenPayload
    {
        $tokenPayload = JWT::decode(
            jwt: $token,
            keyOrKeyArray: new Key(keyMaterial: $secretKey, algorithm: self::ALGORITHM_HS256)
        );

        return new TokenPayload(
            playerId: $tokenPayload->playerId,
            expired: $tokenPayload->exp
        );
    }
}
