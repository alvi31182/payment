<?php

declare(strict_types=1);

namespace App\Tests\KsqlDbTest;

use App\KsqlDbClient\KsqlObject;
use PHPUnit\Framework\TestCase;

class KsqlQueryTest extends TestCase
{
    public function testQuery(): void
    {
        $orderId = '1111';
        $userId = '2222';

        $ksql = new KsqlObject(
            [
                "ORDER_ID" => 12345678,
                "PRODUCT_ID" => "UAC-222-19234",
                "USER_ID" => "User_321"
            ]
        );
        dd($ksql);
    }
}