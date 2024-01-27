<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Receiver;

class AsyncMessageReceiver
{
    public function __construct()
    {
    }

    public function receivers(): void
    {
        dd('444');
    }
}