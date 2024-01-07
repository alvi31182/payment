<?php

namespace App\Tests\Payment\Model;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Model\Event\PaymentCreated;
use App\Payment\Model\Payment;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PaymentTest extends TestCase
{
    public function testCreateDepositRecordsPaymentCreatedEvent(): void
    {
        $command = new CreatePaymentDepositCommand(
            amount: '12330.00',
            currency: 'RUB',
            playerId: Uuid::uuid7()->toString()
        );

        $payment = Payment::createDeposit($command);

        $pullDomainEvents = $payment->pullDomainEvents();

        $countEvents = $pullDomainEvents;

        $this->assertCount(1, $countEvents);

        $this->assertInstanceOf(PaymentCreated::class, $pullDomainEvents[0]);
        $this->assertEquals($payment->getId()->getId(), $pullDomainEvents[0]->id);
        $this->assertEquals($payment->getMoney()->getAmount(), $pullDomainEvents[0]->amount);
        $this->assertEquals($payment->getPlayerId()->getId(), $pullDomainEvents[0]->playerId);
    }
}
