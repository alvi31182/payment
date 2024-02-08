<?php

declare(strict_types=1);

namespace App\Authenticator\Infrastructure\Doctrine\Repository;

use App\Authenticator\Application\UseCase\UserData;
use App\Authenticator\Domain\Email;
use App\Authenticator\Domain\ReadAccountStorage;
use Doctrine\DBAL\ParameterType;

final class ReadRepository extends BaseDoctrineRepository implements ReadAccountStorage
{

    /**
     * @param Email $email
     *
     * @return iterable<UserData>
     */
    public function getAccountData(Email $email): iterable
    {

        $userDataList = [];
//        try {
            $connection = $this->entityManager->getConnection();
            $sql = <<<SQL
            SELECT email, roles FROM account WHERE email = :email
SQL;
            $statement = $connection->executeQuery(
                sql: $sql,
                params: [
                    'email' => $email->getEmail()
                ],
                types: [
                    'email' => ParameterType::STRING
                ]
            );

            return $statement->fetchAssociative();

//
//        } catch (\Throwable $exception){
//            throw $exception;
//        }

    }
}