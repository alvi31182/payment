<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Api\v1\Controller;

use App\Payment\Application\Query\GetPlayerAmountQuery;
use App\Payment\Application\Request\GetPlayerAmountRequest;
use App\Payment\Application\RequestResolver\GetPlayerAmountResolver;
use App\Payment\Application\UseCase\Get\GetPlayerAmountQueryProcessor;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/get/{playerId}')]
#[OA\Tag(name: 'Payment', description: 'Get amount')]
final class GetAmount extends AbstractController
{
    public function __construct(
        private GetPlayerAmountQueryProcessor $queryProcessor
    ) {
    }

    #[Route(path: '', methods: ['GET'])]
    public function getAmount(
        #[MapRequestPayload(
            resolver: GetPlayerAmountResolver::class
        )] GetPlayerAmountRequest $amountRequest
    ): JsonResponse {
        return new JsonResponse([
            $this->queryProcessor->execute(
                amountQuery: new GetPlayerAmountQuery(
                    playerId: $amountRequest->playerId
                )
            ),
        ]);
    }
}
