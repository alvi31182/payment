<?php

declare(strict_types=1);

namespace App\Payment\Application\RequestResolver;

use App\Payment\Application\Request\AppendDepositRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use InvalidArgumentException;

final class AppendDepositValueResolver implements ValueResolverInterface
{
    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable<AppendDepositRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if ($type === null) {
            return [];
        }

        $requestData = json_decode(
            $request->getContent(),
            true,
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
        );

        if (!is_numeric($requestData['appendAmount'])) {
            throw new InvalidArgumentException('The appendAmount should contain only digits.');
        }

        yield new AppendDepositRequest(
            playerId: $requestData['playerId'],
            appendAmount: $requestData['appendAmount']
        );
    }
}
