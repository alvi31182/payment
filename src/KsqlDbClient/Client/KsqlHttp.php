<?php

declare(strict_types=1);

namespace App\KsqlDbClient\Client;

class KsqlHttp
{
    private const string KSQL = 'ksql';

    private const string QUERY = 'query';

    public function ksql(): string
    {
        return self::KSQL;
    }

    public function query(): string
    {
        return self::QUERY;
    }
}