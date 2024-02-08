<?php

declare(strict_types=1);

namespace App\Authenticator\Application\Resolver;

use App\Authenticator\Application\Request\UserDataRegistration;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RegistrationResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {

        $type = $argument->getType();

        if (UserDataRegistration::class !== $type){
            return [];
        }


        $requestData = json_decode(
            $request->getContent(),
            true,
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
        );


        if (!is_string($requestData['email'])) {
            throw new InvalidArgumentException('The appendAmount should contain only digits.');
        }

        yield new UserDataRegistration(
            email: $requestData['email']
        );
    }
}