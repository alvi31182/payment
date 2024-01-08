<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Api\v1\Controller;

use App\Payment\Application\Command\WithdrawalCommand;
use App\Payment\Application\Request\WithdrawalRequest;
use App\Payment\Application\RequestResolver\WithdrawalValueResolver;
use App\Payment\Application\UseCase\Withdrawal\PaymentWithdrawalProcessor;
use App\Payment\Model\Exception\PaymentNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/withdrawal')]
#[OA\Tag(name: 'Payment', description: 'create deposit')]
final class PaymentWithdrawal extends AbstractController
{
    public function __construct(
        private readonly PaymentWithdrawalProcessor $processor
    ) {
    }

    /**
     * @throws PaymentNotFoundException
     */
    #[Route(methods: ['POST'])]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(type: WithdrawalRequest::class)
            )
        )
    )]
    #[Security(name: "ApiKeyAuth")]
    public function withdrawal(#[MapRequestPayload(
        resolver: WithdrawalValueResolver::class
    )]WithdrawalRequest $request): JsonResponse
    {
        $this->processor->execute(
            command: new WithdrawalCommand(
                withdrawalSum: $request->withdrawalSum,
                playerId: $request->playerId
            )
        );

        return new JsonResponse([
            'success' => Response::HTTP_OK,
        ]);
    }
}
