<?php

declare(strict_types=1);

namespace App\Authenticator\Application\ApiKeyAuth;

use App\Authenticator\Application\UseCase\UserData;
use App\Authenticator\Domain\Email;
use App\Authenticator\Domain\ReadAccountStorage;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class JwtLoginProvider implements UserProviderInterface
{

    public function __construct(
        private ReadAccountStorage $readAccountStorage
    )
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf(
                'Instances of "%s" are not supported.',
                $class
            ));
        }

        throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);

        $refreshUser = $this->readAccountStorage->getAccountData(email: new Email($user->getUserIdentifier()));

//        return new UserData(
//            email: $refreshUser
//        );
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserData::class;
    }

    /**
     * @param string $identifier
     *
     * @return UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $loadFromDataBase = $this->readAccountStorage->getAccountData(
            email: new Email($identifier)
        );


        $roles = json_decode($loadFromDataBase['roles'], true);

        return new UserData($identifier, $roles);

    }
}