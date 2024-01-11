<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Get;

use App\Payment\Application\Query\GetPlayerAmountQuery;
use App\Payment\Application\Response\PlayerAmount;
use App\Payment\Model\ReadPaymentStorage;

final readonly class GetPlayerAmountQueryProcessor
{
    public function __construct(
        private ReadPaymentStorage $readPaymentStorage
    ) {
    }

    public function execute(GetPlayerAmountQuery $amountQuery): PlayerAmount
    {
        return $this->readPaymentStorage->getAmountByPlayerId(playerId: $amountQuery->playerId);
    }
}
