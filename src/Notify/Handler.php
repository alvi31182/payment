<?php

declare(strict_types=1);

namespace App\Notify;

use App\Notify\Email\EmailNotify;
use App\Notify\Infrastructure\Transport\KafkaMessage;
use App\Notify\Phone\PhoneNotify;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(method: 'handle')]
final class Handler
{
    public function handle(KafkaMessage $message): void
    {

        $email = 'a@a.com';
        $emailNotify = new EmailNotify(email: $email);

        $phoneNumber = '+13334445343';

        $phoneNotify = new PhoneNotify(phoneNumber: $phoneNumber);

        echo sprintf(
            'Message %s Email %s , phone number %s',
            $message->getMessage()->payload,
            $emailNotify->email,
            $phoneNotify->phoneNumber
        );
    }
}
