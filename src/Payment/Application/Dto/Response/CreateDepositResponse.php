<?php

declare(strict_types=1);

namespace App\Payment\Application\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Deposit created',
    description: 'After create deposit this response returned',
)]
final readonly class CreateDepositResponse
{
}
