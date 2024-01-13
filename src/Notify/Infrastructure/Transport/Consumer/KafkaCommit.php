<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport\Consumer;

use Psr\Log\LoggerInterface;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use RdKafka\TopicPartition;
use Throwable;

final class KafkaCommit
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function commitMessage(Message $message, KafkaConsumer $consumer): void
    {
        try {
            if (0 === $message->err) {
                $consumer->commitAsync($message);
            }
        } catch (Throwable $exception) {
            $this->logger->critical(message: 'Async commit KAFKA error', context: [
                'message' => $exception->getMessage(),
            ]);
        } finally {
            try {
                if (0 === $message->err) {
                    $topic = $consumer->newTopic(topic_name: $message->topic_name);
                    $part = new TopicPartition($message->topic_name, $message->partition, $message->offset);

                    $topic->offsetStore(partition: $part->getPartition(), offset: $part->getOffset());
                    $consumer->commit($message);
                }
            } catch (Throwable $exception) {
                $this->logger->critical(message: 'Sync commit KAFKA error', context: [
                    'message' => $exception->getMessage(),
                ]);
            }
        }
    }
}
