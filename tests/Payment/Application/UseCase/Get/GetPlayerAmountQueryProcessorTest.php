<?php

declare(strict_types=1);

namespace App\Tests\Payment\Application\UseCase\Get;

use App\Payment\Application\Query\GetPlayerAmountQuery;
use App\Payment\Application\Response\PlayerAmount;
use App\Payment\Application\UseCase\Proccessor\GetPlayerAmountQueryProcessor;
use App\Tests\Payment\Model\TestReadPaymentStorage;
use PHPUnit\Framework\TestCase;

class GetPlayerAmountQueryProcessorTest extends TestCase
{
    public function testGetPlayerAmount(): void
    {
        $readPaymentStorage = new TestReadPaymentStorage();

        $playerId = '018c688a-28b0-7264-9a95-dcf900a75dd9';

        $query = new GetPlayerAmountQuery($playerId);

        $processor = new GetPlayerAmountQueryProcessor($readPaymentStorage);

        $result = $processor->execute($query);

        $this->assertInstanceOf(PlayerAmount::class, $result);
        $this->assertEquals($playerId, $result->playerId);
    }
}
