<?php

declare(strict_types=1);

namespace App\Payment\Application\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Get player amount',
    description: 'Player amount',
    required: ['playerId']
)]
final readonly class GetPlayerAmountRequest
{
    public function __construct(
        #[OA\Property(type: 'string', format: 'uuid', nullable: false)]
        public string $playerId
    ) {
    }
}
