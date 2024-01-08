<?php

declare(strict_types=1);

namespace App\Payment\Application\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Withdrawal sum',
    required: ['withdrawalSum']
)]
final readonly class WithdrawalRequest
{
    /**
     * @param numeric-string $withdrawalSum
     * @param non-empty-string $playerId
     */
    public function __construct(
        #[OA\Property(type: 'string', format: 'numeric', nullable: false)]
        public string $withdrawalSum,
        #[OA\Property(type: 'string', format: 'uuid', nullable: false)]
        public string $playerId,
    ) {
    }
}
