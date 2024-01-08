<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Deposit;

use App\Payment\Application\Command\AddPaymentDepositCommand;
use App\Payment\Application\UseCase\UseCaseException\AppendPaymentException;
use App\Payment\Infrastructure\Persistence\TransactionProcessor;
use App\Payment\Model\Exception\PaymentNotFoundException;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use Throwable;

final readonly class AppendPaymentDepositProcessor
{
    public function __construct(
        private WritePaymentStorage $writePaymentStorage,
        private ReadPaymentStorage $readPaymentStorage,
        private TransactionProcessor $transactionProcessor
    ) {
    }

    /**
     * @throws AppendPaymentException
     */
    public function execute(AddPaymentDepositCommand $command): void
    {
        try {
            $payment = $this->readPaymentStorage->findPaymentByPlayerId(
                playerId: $command->playerId
            );

            if ($payment === null) {
                throw new PaymentNotFoundException('Not found payment by player ID.');
            }

            $this->transactionProcessor->transactional(operation: function () use ($payment, $command): void {
                $this->writePaymentStorage->appendDeposit(
                    $payment->appendDeposit(depositAmount: $command->appendAmount)
                );
            });
        } catch (Throwable $exception) {
            throw new AppendPaymentException(
                $exception->getMessage()
            );
        }
    }
}
