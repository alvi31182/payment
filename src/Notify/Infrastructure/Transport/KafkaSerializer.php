<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport;

use App\Notify\Infrastructure\Transport\Exception\KafkaTransportException;
use RdKafka\Message;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class KafkaSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        if (!isset($encodedEnvelope['message'])) {
            throw new KafkaTransportException(
                'Encoded envelop array BODY can`t be empty.'
            );
        }

        /** @var Message $message */
        $message = $encodedEnvelope['message'];
        $messageDecode = [];

        $isJson = json_validate($message->payload, JSON_THROW_ON_ERROR);

        if ($isJson) {
            $messageDecode[] = json_decode(
                $message->payload,
                true,
                JSON_THROW_ON_ERROR
            );
        }

        $kFMessage = new KafkaMessage(
            payload: $messageDecode,
            message: $message
        );

        return (new Envelope($kFMessage))->with();
    }

    /**
     * @return array<empty>
     */
    public function encode(Envelope $envelope): array
    {
        return [];
    }
}
