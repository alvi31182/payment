<?php

declare(strict_types=1);

namespace App\KsqlDbClient;

enum Query: string
{
    case SELECT = 'SELECT';
    case STREAM = 'STREAM';
    case COUNT = 'COUNT';
    case AS = 'AS';
    case WHERE = 'WHERE';
    case TABLE = 'TABLE';
    case GROUP_BY = 'GROUP BY';
    case EMIT_CHANGES = 'EMIT CHANGES';
}