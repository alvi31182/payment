<?php

declare(strict_types=1);

namespace App\EventStorage\Model;

interface WriteEventStorage
{
    public function create(EventStorage $eventStorage): void;
}