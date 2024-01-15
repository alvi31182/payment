<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport\Consumer;

use App\Notify\Infrastructure\Transport\KafkaSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Exception;

final readonly class KafkaReceiver implements ReceiverInterface
{
    private SerializerInterface $serializer;

    public function __construct(
        private string $kafkaBrokerList,
        private string $consumerGroup,
        private string $topicPrefix,
        private array $kafkaTopics,
        private LoggerInterface $logger,
        SerializerInterface $serializer = null
    ) {
        $this->serializer = $serializer ?? new KafkaSerializer();
    }

    /**
     * @psalm-suppress UndefinedConstant
     * @return \Generator<Envelope>
     * @throws \RdKafka\Exception
     */
    public function get(): iterable
    {
        $consumer = $this->kafkaConsumer()->consumeFromKafka();

        $message = $consumer->consume(timeout_ms: 2_000);

        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $envelope = $this->serializer->decode(
                    [
                        'message' => $message,
                    ]
                );
                yield from [$envelope->with(new TransportMessageIdStamp($message->key))];
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                echo "No more messages; will wait for more\n";
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                echo "Timed out\n";
                break;
            default:
                throw new Exception($message->errstr(), $message->err);
        }
    }

    public function ack(Envelope $envelope): void
    {
        // TODO: Implement ack() method.
    }

    public function reject(Envelope $envelope): void
    {
        // TODO: Implement reject() method.
    }

    public function kafkaConsumer(): KafkaConsumerConfiguration
    {
        return new KafkaConsumerConfiguration(
            kafkaBrokerList: $this->kafkaBrokerList,
            consumerGroup: $this->consumerGroup,
            topicPrefix: $this->topicPrefix,
            kafkaTopics: $this->kafkaTopics,
            logger: $this->logger
        );
    }
}
