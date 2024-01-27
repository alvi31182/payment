<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Locator;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Symfony\Contracts\Service\ServiceProviderInterface;

class AsyncLocator implements ServiceProviderInterface
{
    public function get(string $id): mixed
    {
        dd('dd');
    }

    public function has(string $id): bool
    {
        dd('has');
    }

    public function getProvidedServices(): array
    {
        dd('eee');
    }

}