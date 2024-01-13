<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport\Consumer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;

final readonly class KafkaConsumerTransport implements ReceiverInterface
{
    public function __construct(
        private string $kafkaBrokerList,
        private string $consumerGroup,
        private array $kafkaTopics
    )
    {
    }

    public function get(): iterable
    {

    }

    public function ack(Envelope $envelope): void
    {
        // TODO: Implement ack() method.
    }

    public function reject(Envelope $envelope): void
    {
        // TODO: Implement reject() method.
    }
}