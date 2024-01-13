<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use SensitiveParameter;

final class KafkaTransportFactory implements TransportFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function createTransport(
        #[SensitiveParameter] string $dsn,
        array $options,
        SerializerInterface $serializer
    ): TransportInterface {
        return new KafkaTransport(
            options: $options,
            logger: $this->logger
        );
    }

    public function supports(#[SensitiveParameter] string $dsn, array $options): bool
    {
        return 0 === strrpos($dsn, 'payment_kafka://');
    }
}
