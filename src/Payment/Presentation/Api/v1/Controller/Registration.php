<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Api\v1\Controller;

use App\Authenticator\Application\Handler\RegistrationHandler;
use App\Authenticator\Application\Request\UserDataRegistration;
use App\Authenticator\Application\Resolver\RegistrationResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[AsController]
#[Route(path: '/api/v1/registration')]
#[OA\Tag(name: 'Payment', description: 'registration')]
class Registration extends AbstractController
{
    #[Route(path: '', methods: ['POST'])]
    public function registration(
        UserDataRegistration $dataRegistration,
        RegistrationHandler $registrationHandler
    ): JsonResponse
    {

        return new JsonResponse(
            [
                'registered' => $registrationHandler->handler($dataRegistration->email)
            ]
        );
    }
}