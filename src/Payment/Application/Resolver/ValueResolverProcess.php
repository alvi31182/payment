<?php

declare(strict_types=1);

namespace App\Payment\Application\Resolver;

use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;

class ValueResolverProcess
{
    public function __construct(
        private DenormalizableInterface $denormalizable
    ) {
    }

    public function den(object $class)
    {
        dd($class);
    }
}
