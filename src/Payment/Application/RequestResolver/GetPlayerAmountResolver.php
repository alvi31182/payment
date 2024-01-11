<?php

declare(strict_types=1);

namespace App\Payment\Application\RequestResolver;

use App\Payment\Application\Request\GetPlayerAmountRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class GetPlayerAmountResolver implements ValueResolverInterface
{
    /**
     * @return iterable<GetPlayerAmountRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if ($type === null) {
            return [];
        }

        yield new GetPlayerAmountRequest(
            playerId: $request->attributes->get('playerId')
        );
    }
}
