<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use Exception;
use Serializable;

final class EventObjectSerialize implements Serializable
{
    public function __construct(
        private array $data
    )
    {

    }

    public function serialize(): ?string
    {
        return serialize($this->data);
    }

    public function unserialize(string $data): void
    {
        $this->data = unserialize($data);
    }

    public function __serialize(): array
    {
        return $this->data;
    }

    public function __unserialize(array $data): void
    {
        $this->data = $data;
    }
}