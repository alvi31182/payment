<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Api\v1\Controller;

use App\Payment\Application\Command\AddPaymentDepositCommand;
use App\Payment\Application\Request\AppendDepositRequest;
use App\Payment\Application\RequestResolver\AppendDepositValueResolver;
use App\Payment\Application\UseCase\Deposit\AppendPaymentDepositProcessor;
use App\Payment\Application\UseCase\UseCaseException\AppendPaymentException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/appendDeposit')]
#[OA\Tag(name: 'Payment', description: 'append deposit')]
final class AppendDeposit extends AbstractController
{
    public function __construct(
        private AppendPaymentDepositProcessor $processor
    ) {
    }

    /**
     * @throws AppendPaymentException
     */
    #[Route(methods: ['POST'])]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: AppendDepositRequest::class
                )
            )
        )
    )]
    #[Security(name: "ApiKeyAuth")]
    public function appendDeposit(
        #[MapRequestPayload(
            resolver: AppendDepositValueResolver::class
        )]AppendDepositRequest $request
    ): JsonResponse {
        $this->processor->execute(
            command: new AddPaymentDepositCommand(
                playerId: $request->playerId,
                appendAmount: $request->appendAmount
            )
        );
        return new JsonResponse(
            [
                'success' => Response::HTTP_CREATED,
            ]
        );
    }
}
