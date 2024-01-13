<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport;

use RdKafka\Message;

final class KafkaMessage
{
    public function __construct(
        private array $payload,
        private Message $message
    ) {
    }

    /**
     * @return array<array-key, string|array|int>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
