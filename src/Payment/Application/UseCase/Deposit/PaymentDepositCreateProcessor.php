<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Deposit;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Application\Converter\Money\MoneyConverter;
use App\Payment\Infrastructure\Persistence\TransactionProcessor;
use App\Payment\Model\Event\AsyncEventDispatcherInterface;
use App\Payment\Model\Payment;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use Throwable;

final readonly class PaymentDepositCreateProcessor
{
    public function __construct(
        private TransactionProcessor $transactionProcessor,
        private WritePaymentStorage $writePaymentStorage,
        private ReadPaymentStorage $readPaymentStorage,
        private AsyncEventDispatcherInterface $eventDispatcher
    ) {
    }

    public function execute(CreatePaymentDepositCommand $command): void
    {
        $player = $this->readPaymentStorage->findPlayerById(playerId: $command->playerId);
        $pennies = MoneyConverter::convertToNumeric($command->amount);
        $payment = Payment::createDeposit(command: $command, pennies: $pennies);
        if (!$player) {
            try {

                foreach ($payment->pullDomainEvents() as $event){
                    $this->eventDispatcher->dispatch($event);
                }

            } catch (Throwable $exception) {
                throw new RuntimeException(
                    $exception->getMessage()
                );
            }
        }
    }
}
