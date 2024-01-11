<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Withdrawal;

use App\Payment\Application\Command\WithdrawalCommand;
use App\Payment\Infrastructure\Persistence\TransactionProcessor;
use App\Payment\Model\Exception\PaymentNotFoundException;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use RuntimeException;

use function bccomp;

final readonly class PaymentWithdrawalProcessor
{
    public function __construct(
        private TransactionProcessor $transactionProcessor,
        private ReadPaymentStorage $readPaymentStorage,
        private WritePaymentStorage $writePaymentStorage
    ) {
    }

    /**
     * @throws PaymentNotFoundException
     */
    public function execute(WithdrawalCommand $command): void
    {
        $payment = $this->readPaymentStorage->findPaymentByPlayerId(playerId: $command->playerId);

        if ($payment === null) {
            throw new PaymentNotFoundException(
                message: 'Payment not found by this player ID'
            );
        }

        if (bccomp(num1: $payment->getMoney()->getAmount(), num2: $command->withdrawalSum) === -1) {
            throw new RuntimeException(
                message: 'The requested amount is more than the actual amount in your account sum: '
                . $payment->getMoney()->getAmount()
            );
        }
        $this->transactionProcessor->transactional(
            operation: function () use ($payment, $command): void {
                $this->writePaymentStorage->withdrawal(
                    payment: $payment->withdrawal(withdrawalAmount: $command->withdrawalSum)
                );
            }
        );
    }
}
