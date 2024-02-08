<?php

declare(strict_types=1);

namespace App\Authenticator\Infrastructure\Doctrine\Mapping;



use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class JsonB extends Type
{
    public const string TYPE = 'jsonb';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'JSONB';
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_resource($value)){
            $value = stream_get_contents($value);
        }

        return json_decode($value, true, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    /**
     * @throws \JsonException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null){
            return null;
        }

        if (!is_string($value)){
            return json_encode($value, JSON_THROW_ON_ERROR);
        }

        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): true
    {
        return true;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}