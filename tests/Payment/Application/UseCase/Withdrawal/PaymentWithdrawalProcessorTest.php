<?php

declare(strict_types=1);

namespace App\Tests\Payment\Application\UseCase\Withdrawal;

use App\Payment\Application\Command\WithdrawalCommand;
use App\Payment\Application\UseCase\Withdrawal\PaymentWithdrawalProcessor;
use App\Payment\Model\Enum\AmountType;
use App\Payment\Model\Exception\PaymentNotFoundException;
use App\Payment\Model\Money;
use App\Payment\Model\Payment;
use App\Payment\Model\PaymentId;
use App\Payment\Model\PlayerId;
use App\Tests\Payment\Model\TestReadPaymentStorage;
use App\Tests\Payment\Model\TestWritePaymentStorage;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use RuntimeException;

class PaymentWithdrawalProcessorTest extends TestCase
{
    /**
     * @requires extension bcmath
     * @throws PaymentNotFoundException
     */
    public function testSuccessfulWithdrawal(): void
    {
        $playerId = '018ce31c-a470-7078-93c5-8ccfe56047e9';
        $withdrawalSum = "500.11";

        $payment = new Payment(
            id: new PaymentId(Uuid::fromString('018ce0a6-ce6b-7152-acd1-0a2f3a096747')),
            money: new Money('1000', 'RUB'),
            playerId: new PlayerId($playerId),
            amountType: AmountType::DEPOSIT,
            createdAt: new DateTimeImmutable(),
            updatedAt: null
        );

        $readPaymentStorage = new TestReadPaymentStorage(
            payment: $payment
        );

        $writePaymentStorage = new TestWritePaymentStorage();

        $processor = new PaymentWithdrawalProcessor($readPaymentStorage, $writePaymentStorage);
        $processor->execute(new WithdrawalCommand(withdrawalSum: $withdrawalSum, playerId: $playerId));

        $this->assertEquals(
            '499.89',
            $payment->getMoney()->getAmount(),
        );
    }

    /**
     * @requires extension bcmath
     * @throws PaymentNotFoundException
     */
    public function testInsufficientFunds(): void
    {
        $playerId = '018ce31c-a470-7078-93c5-8ccfe56047e9';
        $withdrawalSum = '1000.11';

        $payment = new Payment(
            id: new PaymentId(Uuid::fromString('018ce0a6-ce6b-7152-acd1-0a2f3a096747')),
            money: new Money('100.34', 'RUB'),
            playerId: new PlayerId($playerId),
            amountType: AmountType::WITHDRAWAL,
            createdAt: new DateTimeImmutable(),
            updatedAt: null
        );

        $readPaymentStorage = new TestReadPaymentStorage(payment: $payment);
        $writePaymentStorage = new TestWritePaymentStorage();

        $processor = new PaymentWithdrawalProcessor(
            readPaymentStorage: $readPaymentStorage,
            writePaymentStorage: $writePaymentStorage
        );

        $this->expectException(RuntimeException::class);

        $processor->execute(new WithdrawalCommand(withdrawalSum: $withdrawalSum, playerId: $playerId));
    }
}
