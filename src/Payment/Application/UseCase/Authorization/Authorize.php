<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Authorization;

use App\Payment\Application\Exception\InvalidTokenException;
use App\Payment\Application\Exception\UnauthorizedException;
use App\Payment\Application\UseCase\TokenOperation\JwtTokenGenerator;
use App\Payment\Application\UseCase\TokenOperation\JwtTokenValidator;

final readonly class Authorize
{
    public function __construct(
        private JwtTokenGenerator $tokenGenerator,
        private JwtTokenValidator $tokenValidator
    ) {
    }

    public function generateToken(array $data, string $secretKey, int $expirationTimeInSeconds): string
    {
        return $this->tokenGenerator->generateToken(
            data: $data,
            secretKey: $secretKey,
            expirationTimeInSeconds: $expirationTimeInSeconds
        );
    }

    /**
     * @throws UnauthorizedException
     * @throws InvalidTokenException
     */
    public function tokenValidator(string $token, string $secretKey, int $allowedClockSkewInSeconds = 99999): void
    {
        $this->tokenValidator->validateToken(
            token: $token,
            secretKey: $secretKey,
            allowedClockSkewInSeconds: $allowedClockSkewInSeconds
        );
    }
}
