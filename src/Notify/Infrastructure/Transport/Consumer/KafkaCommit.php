<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport\Consumer;

use Psr\Log\LoggerInterface;
use RdKafka\Exception;
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
            if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                $consumer->commitAsync($message);
            }
        } catch (Throwable $exception) {
            $this->logCommitError(
                type: 'Async',
                exception: $exception,
                message: $message
            );
        } finally {
            try {
                if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                    $this->commitSync(consumer: $consumer, message: $message);
                }
            } catch (Throwable $exception) {
                $this->logger->critical(message: 'Sync commit KAFKA error', context: [
                    'message' => $exception->getMessage(),
                ]);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function commitSync(KafkaConsumer $consumer, Message $message): void
    {
        $topic = $consumer->newTopic(topic_name: $message->topic_name);
        $part = new TopicPartition($message->topic_name, $message->partition, $message->offset);

        $topic->offsetStore(partition: $part->getPartition(), offset: $part->getOffset());
        $consumer->commit($message);
    }

    private function logCommitError(string $type, Throwable $exception, Message $message): void
    {
        $this->logger->critical(
            message: sprintf('commit KAFKA error in type %s ', $type),
            context: [
                'message' => $exception->getMessage(),
                'topic' => $message->topic_name,
                'partition' => $message->partition,
                'offset' => $message->offset,
            ]
        );
    }
}
