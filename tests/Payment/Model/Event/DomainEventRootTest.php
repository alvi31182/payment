<?php

declare(strict_types=1);

namespace App\Tests\Payment\Model\Event;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Model\Payment;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DomainEventRootTest extends TestCase
{
    public function testPullDomainEventsClearsEventsArray(): void
    {
        $command = new CreatePaymentDepositCommand(
            amount: '2000.00',
            currency: 'RUB',
            playerId: Uuid::uuid7()->toString()
        );

        $payment = Payment::createDeposit($command);

        $events = $payment->pullDomainEvents();

        $this->assertCount(1, $events);
        $this->assertCount(0, $payment->pullDomainEvents());
    }
}
