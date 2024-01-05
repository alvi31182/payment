<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Create;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Application\UseCase\ArgumentProcessor;
use App\Payment\Model\Payment;
use App\Payment\Model\WritePaymentStorage;
use Throwable;
use RuntimeException;

#[ArgumentProcessor]
final readonly class PaymentDepositCreateProcessor
{
    public function __construct(
        private WritePaymentStorage $writePaymentStorage
    ) {
    }

    public function execute(CreatePaymentDepositCommand $command): void
    {
        try {
            $this->writePaymentStorage->createDeposit(
                Payment::createDeposit(command: $command)
            );
        } catch (Throwable $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }
    }
}
