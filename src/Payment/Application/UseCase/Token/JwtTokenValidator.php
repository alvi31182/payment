<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Token;

use App\Payment\Application\Exception\InvalidTokenException;
use App\Payment\Application\Exception\UnauthorizedException;
use App\Payment\Model\ReadPaymentStorage;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

final class JwtTokenValidator
{
    private const ALGORITHM_HS256 = 'HS256';

    public function __construct(
        private readonly ReadPaymentStorage $readPaymentStorage
    ) {
    }

    /**
     * @throws InvalidTokenException
     * @throws UnauthorizedException
     */
    public function validateToken(string $token, string $secretKey, int $allowedClockSkewInSeconds = 99999): void
    {
        $tokenWithoutBearer = str_replace('Bearer ', '', $token);

        try {
            $payloadObject = JWT::decode($tokenWithoutBearer, new Key($secretKey, self::ALGORITHM_HS256));
        } catch (SignatureInvalidException $e) {
            throw new InvalidTokenException('Invalid signature', 0, $e);
        } catch (BeforeValidException $e) {
            throw new InvalidTokenException('Token is not yet valid', 0, $e);
        } catch (ExpiredException $e) {
            throw new InvalidTokenException('Expired token', 0, $e);
        }

        $this->validatePlayerId($payloadObject->playerId);
        $this->validateTokenExpiration($payloadObject->exp, $allowedClockSkewInSeconds);
    }

    /**
     * @throws UnauthorizedException
     */
    private function validatePlayerId(string $playerId): void
    {
        if (!$this->readPaymentStorage->isPlayerIdExists($playerId)) {
            throw new UnauthorizedException('Access denied');
        }
    }

    /**
     * @throws InvalidTokenException
     */
    private function validateTokenExpiration(int $expirationTime, int $allowedClockSkewInSeconds): void
    {
        if ($expirationTime < (time() - $allowedClockSkewInSeconds)) {
            throw new InvalidTokenException('Expired token');
        }
    }
}
