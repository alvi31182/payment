<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Deposit;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Application\Converter\Money\MoneyConverter;
use App\Payment\Infrastructure\Persistence\TransactionProcessor;
use App\Payment\Model\Payment;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use RuntimeException;
use Throwable;

final readonly class PaymentDepositCreateProcessor
{
    public function __construct(
        private TransactionProcessor $transactionProcessor,
        private WritePaymentStorage $writePaymentStorage,
        private ReadPaymentStorage $readPaymentStorage
    ) {
    }

    public function execute(CreatePaymentDepositCommand $command): void
    {
        $player = $this->readPaymentStorage->findPlayerById(playerId: $command->playerId);
        $pennies = MoneyConverter::convertToNumeric($command->amount);

        if (!$player) {
            try {
                $this->transactionProcessor->transactional(
                    function () use ($command, $pennies): void {
                        $this->writePaymentStorage->createDeposit(
                            Payment::createDeposit(command: $command, pennies: $pennies)
                        );
                    }
                );
            } catch (Throwable $exception) {
                throw new RuntimeException(
                    $exception->getMessage()
                );
            }
        }
    }
}
