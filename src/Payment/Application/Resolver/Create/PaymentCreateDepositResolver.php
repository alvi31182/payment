<?php

declare(strict_types=1);

namespace App\Payment\Application\Resolver\Create;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Override;

final class PaymentCreateDepositResolver implements ValueResolverInterface
{
    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return array<array-key, string>
     */
    #[Override] public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {

        $type = $argument->getName();
        if (is_null($type)) {
            return ['w'];
        }
        return ['e'];
    }
}
