<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Token;

use App\Payment\Application\Exception\InvalidTokenException;
use App\Payment\Application\Exception\UnauthorizedException;
use App\Payment\Model\ReadPaymentStorage;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

final readonly class JwtTokenValidator
{
    public function __construct(
        private ReadPaymentStorage $readPaymentStorage,
        private JwtTokenDecoder $tokenDecoder
    ) {
    }

    /**
     * @throws InvalidTokenException
     * @throws UnauthorizedException
     */
    public function validateToken(string $token, string $secretKey, int $allowedClockSkewInSeconds = 99999): void
    {
        $tokenPayload = $this->decodeToken(token: $token, secretKey: $secretKey);
        $this->validatePlayerId($tokenPayload->playerId);
        $this->validateTokenExpiration($tokenPayload->expired, $allowedClockSkewInSeconds);
    }

    /**
     * @throws InvalidTokenException
     */
    private function decodeToken(string $token, string $secretKey): TokenPayload
    {
        $tokenWithoutBearer = str_replace('Bearer ', '', $token);

        try {
            return $this->tokenDecoder->decode(token: $tokenWithoutBearer, secretKey: $secretKey);
        } catch (SignatureInvalidException $e) {
            throw new InvalidTokenException('Invalid signature', 0, $e);
        } catch (BeforeValidException $e) {
            throw new InvalidTokenException('Token is not yet valid', 0, $e);
        } catch (ExpiredException $e) {
            throw new InvalidTokenException('Expired token', 0, $e);
        } catch (UnexpectedValueException $e) {
            throw new InvalidTokenException('Invalid token', 0, $e);
        }
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
