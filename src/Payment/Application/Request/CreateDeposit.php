<?php

declare(strict_types=1);

namespace App\Payment\Application\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Deposit create',
    description: 'Create new Deposit in Payment service',
    required: ['amount', 'currency', 'playerId'],
)]
final readonly class CreateDeposit
{
    public function __construct(
        #[OA\Property(type: 'string', nullable: false)]
        public string $amount,
        #[OA\Property(type: 'string', nullable: false)]
        public string $currency,
        #[OA\Property(type: 'string', nullable: false)]
        public string $playerId
    ) {
    }
}
