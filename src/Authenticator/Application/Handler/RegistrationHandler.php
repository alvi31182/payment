<?php

declare(strict_types=1);

namespace App\Authenticator\Application\Handler;

use App\Authenticator\Domain\Email;
use App\Authenticator\Domain\Account;
use App\Authenticator\Domain\UserId;
use App\Authenticator\Domain\UserWriteStorage;
use App\Authenticator\Domain\WriteAccountProfile;
use App\Payment\Infrastructure\Persistence\TransactionProcessor;
use Firebase\JWT\JWT;

final readonly class RegistrationHandler
{

    public function __construct(
        private UserWriteStorage $userWriteStorage,
        private WriteAccountProfile $writeAccountProfile,
        private TransactionProcessor $transactionProcessor
    )
    {
    }

    /**
     * @param string $email
     *
     * @return non-empty-string
     */
    public function handler(string $email): string
    {
        $user = new Account(
            id: UserId::generateUuidV7(),
            email: new Email($email),
            password: '2345',
            roles: ['ROLE_USER']
        );

        $payload = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'exp' => time() + 999
        ];

        $this->transactionProcessor->transactional(transaction: function () use ($user){
            $this->userWriteStorage->save(
                $user
            );
        });

        $jwt = JWT::encode(payload: $payload, key: $_ENV['JWT_SECRET_KEY'], alg: 'HS256');

        return $jwt;
    }
}