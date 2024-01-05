<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Api\v1\Controller;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Application\Dto\Request\CreateDeposit;
use App\Payment\Application\Resolver\PaymentCreateDepositResolver;
use App\Payment\Application\UseCase\Create\PaymentDepositCreateProcessor;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/create')]
#[OA\Tag(name: 'payment', description: 'create deposit')]
final class PaymentCreateDeposit extends AbstractController
{
    public function __construct(
        private readonly PaymentDepositCreateProcessor $createProcessor
    ) {
    }

    #[Route(path: '', methods: ['POST'])]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(type: CreateDeposit::class)
            )
        )
    )]
    #[Security(name: 'Bearer')]
    public function createDeposit(
        #[MapRequestPayload(
            resolver: PaymentCreateDepositResolver::class
        )] CreateDeposit $request
    ): JsonResponse {

        $this->createProcessor->execute(
            new CreatePaymentDepositCommand(
                amount: $request->amount,
                currency: $request->currency,
                playerId: $request->playerId
            )
        );

        return new JsonResponse([
            'success' => JsonResponse::HTTP_CREATED,
        ]);
    }
}
