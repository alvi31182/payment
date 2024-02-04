<?php

namespace App\Tests\KsqlDBTestQuery;

use App\KsqlDbClient\Client\KsqlDbBuildQuery;
use App\KsqlDbClient\Client\KsqlHttp;
use PHPUnit\Framework\TestCase;

class KsqlDbTest extends TestCase
{
    public function testQuery()
    {
        $buildKsqlQuery = new KsqlDbBuildQuery();

        $ksqlQuery = "CREATE STREAM pageviews_home AS SELECT ?S FROM pageviews_original WHERE pageid IN ?WS AND ?AS;";
        $query = $buildKsqlQuery->createStream(
            $ksqlQuery,
            [
                ['name','last'],
               'where' => ['one', 'two'],
               'and' => ['three','four']
            ]
        );



        dd($query);
    }

    public function queryText(): string
    {
        $ksqlQuery = "CREATE STREAM pageviews_home AS SELECT * FROM pageviews_original WHERE pageid='home';";

        $pattern = '/CREATE\s+STREAM\s+\w+\s+AS\s+SELECT\s*\*\s*FROM\s+\w+\s+WHERE\s+.+;/i';

        $pregMatch = preg_match($pattern, $ksqlQuery, $matches);


        dd($matches);
    }
}