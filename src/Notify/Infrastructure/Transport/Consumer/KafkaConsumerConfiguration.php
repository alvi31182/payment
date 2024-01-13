<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport\Consumer;

use Exception;
use Psr\Log\LoggerInterface;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\TopicPartition;

final readonly class KafkaConsumerConfiguration
{
    public function __construct(
        private string $kafkaBrokerList,
        private string $consumerGroup,
        private string $topicPrefix,
        private array $kafkaTopics,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @psalm-suppress UndefinedConstant
     * @psalm-suppress UndefinedFunction
     */
    public function conf(): Conf
    {
        $conf = new Conf();
        $conf->set('metadata.broker.list', $this->kafkaBrokerList);
        $conf->set('group.id', $this->topicPrefix . $this->consumerGroup);
        $conf->set('enable.auto.commit', 'false');
        $conf->set('enable.auto.offset.store', 'false');
        $conf->set('auto.commit.interval.ms', '1000');
        $conf->set('session.timeout.ms', '36000');
        $conf->set('enable.partition.eof', 'true');
        $conf->setErrorCb(
            function (mixed $error, string $reason): void {
                if (RD_KAFKA_RESP_ERR__FATAL === $error) {
                    $this->logger->critical(
                        message: 'KAFKA FATAL ERROR',
                        context: [
                            'message' => sprintf('Error %d %s. Reason: %s', $error, rd_kafka_err2str($error), $reason),
                        ],
                    );
                }
            },
        );

        /* @psalm-suppress UndefinedConstant */
        $conf->setRebalanceCb(function (KafkaConsumer $kafka, string $err, array $partitions): void {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    /** @var TopicPartition $topicPartition */
                    foreach ($partitions as $topicPartition) {
                        $this->logger->info(
                            sprintf(
                                'Assign: %s %d %d',
                                $topicPartition->getTopic(),
                                $topicPartition->getPartition(),
                                $topicPartition->getOffset(),
                            ),
                        );
                    }
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    /** @var TopicPartition $topicPartition */
                    foreach ($partitions as $topicPartition) {
                        $this->logger->info(
                            sprintf(
                                'Assign: %s %d %d',
                                $topicPartition->getTopic(),
                                $topicPartition->getPartition(),
                                $topicPartition->getOffset(),
                            ),
                        );
                    }

                    $kafka->assign(null);
                    break;
                default:
                    throw new Exception($err);
            }
        });

        return $conf;
    }

    public function kafkaConsumer(): KafkaConsumer
    {
        return new KafkaConsumer(
            $this->conf()
        );
    }

    public function consumeFromKafka(): KafkaConsumer
    {
            $consumer = $this->kafkaConsumer();
            $consumer->subscribe(topics: $this->kafkaTopics);
            return $consumer;
    }
}
