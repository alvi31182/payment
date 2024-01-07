<?php

declare(strict_types=1);

namespace App\Payment\Application\RequestResolver;

use App\Payment\Application\Request\WithdrawalRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class WithdrawalValueResolver implements ValueResolverInterface
{
    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable<WithdrawalRequest>
     * @throws \JsonException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if ($type === null) {
            return [];
        }

        $requestData = json_decode(
            json: $request->getContent(),
            associative: true,
            flags: JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT,
        );

        yield new WithdrawalRequest(
            withdrawalSum: $requestData['withdrawalSum'],
            playerId: $requestData['playerId']
        );
    }
}
