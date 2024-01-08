<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Deposit;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Application\UseCase\ArgumentProcessor;
use App\Payment\Infrastructure\Persistence\TransactionProcessor;
use App\Payment\Model\Payment;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use RuntimeException;
use Throwable;

#[ArgumentProcessor]
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

        if (!$player) {
            try {
                $this->transactionProcessor->transactional(
                    function () use ($command): void {
                        $this->writePaymentStorage->createDeposit(
                            Payment::createDeposit(command: $command)
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
