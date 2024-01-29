<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use Attribute;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

interface AsyncListenerInterface
{
}