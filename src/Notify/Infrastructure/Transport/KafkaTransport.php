<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport;

use App\Notify\Infrastructure\Transport\Consumer\KafkaCommit;
use App\Notify\Infrastructure\Transport\Consumer\KafkaReceiver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

final readonly class KafkaTransport implements TransportInterface
{
    /**
     * @param array{
     *      kafka_broker: string,
     *      group_id: string,
     *      kafka_topic_prefix: string,
     *      consumer_topics: array
     * } $options
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        private array $options,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return iterable<Envelope>
     */
    public function get(): iterable
    {
        return $this->getKafkaConsumerTransport()->get();
    }

    public function ack(Envelope $envelope): void
    {
        /**_**/
    }

    public function reject(Envelope $envelope): void
    {
        /**_**/
    }

    public function send(Envelope $envelope): Envelope
    {
        /** @var KafkaMessage $message **/
        $message = $envelope->getMessage();

        $consumerConfiguration = $this->getKafkaConsumerTransport()->kafkaConsumer();

        (new KafkaCommit($this->logger))
            ->commitMessage($message->getMessage(), $consumerConfiguration->kafkaConsumer());

        return $envelope->with(new TransportMessageIdStamp(
            $message->getMessage()->key
        ));
    }

    private function getKafkaConsumerTransport(): KafkaReceiver
    {
        return new KafkaReceiver(
            kafkaBrokerList: $this->options['kafka_broker'],
            consumerGroup: $this->options['group_id'],
            topicPrefix: $this->options['kafka_topic_prefix'],
            kafkaTopics: $this->options['consumer_topics'],
            logger: $this->logger
        );
    }
}
