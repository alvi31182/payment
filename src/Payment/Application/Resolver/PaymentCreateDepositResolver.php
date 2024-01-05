<?php

declare(strict_types=1);

namespace App\Payment\Application\Resolver;

use App\Payment\Application\Dto\Request\CreateDeposit;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class PaymentCreateDepositResolver implements ValueResolverInterface
{
    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return array<array-key, string>
     * @throws \JsonException
     */
    #[Override] public function resolve(Request $request, ArgumentMetadata $argument): iterable
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


        return [new CreateDeposit(
            amount: $requestData['amount'],
            currency: $requestData['currency'],
            playerId: $requestData['playerId']
        )];
    }
}
