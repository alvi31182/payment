<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Locator;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

interface AsyncMessageLocator
{
    public function deferred(Deferred $deferred): PromiseInterface;
}