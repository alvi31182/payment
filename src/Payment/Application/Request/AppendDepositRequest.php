<?php

declare(strict_types=1);

namespace App\Payment\Application\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Append deposit',
    description: 'Append deposit for player',
    required: ['playerId', 'appendAmount'],
)]
final class AppendDepositRequest
{
    /**
     * @param non-empty-string $playerId
     * @param numeric-string $appendAmount
     */
    public function __construct(
        #[OA\Property(type: 'string', format: 'uuid', nullable: false)]
        public string $playerId,
        #[OA\Property(type: 'string', format: 'numeric', nullable: false)]
        public string $appendAmount
    ) {
    }
}
