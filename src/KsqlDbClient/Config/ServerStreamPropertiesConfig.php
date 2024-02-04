<?php

declare(strict_types=1);

namespace App\KsqlDbClient\Config;

class ServerStreamPropertiesConfig
{
    public const array OPTIONS = [
        'ksql.advertised.listener' => '',
        'ksql.assert.topic.default.timeout.ms' => 1000,
        'ksql.connect.url' => 'http://connect:8083',
        'ksql.connect.worker.config' => '',
        'ksql.fail.on.deserialization.error' => false,
    ];
}