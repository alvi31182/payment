<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Api\v1\Controller;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Application\Request\CreateDepositRequest;
use App\Payment\Application\RequestResolver\CreateDepositValueResolver;
use App\Payment\Application\UseCase\Deposit\PaymentDepositCreateProcessor;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/create')]
#[OA\Tag(name: 'Payment', description: 'create deposit')]
final class PaymentCreateDeposit extends AbstractController
{
    public function __construct(
        private readonly PaymentDepositCreateProcessor $createProcessor
    ) {
    }

    #[Route(methods: ['POST'])]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(type: CreateDepositRequest::class)
            )
        )
    )]
    #[Security(name: "ApiKeyAuth")]
    public function createDeposit(
        #[MapRequestPayload(
            resolver: CreateDepositValueResolver::class
        )] CreateDepositRequest $request
    ): JsonResponse {

        $this->createProcessor->execute(
            new CreatePaymentDepositCommand(
                amount: $request->amount,
                currency: $request->currency,
                playerId: $request->playerId
            )
        );

        return new JsonResponse([
            'success' => Response::HTTP_CREATED,
        ]);
    }
}
