<?php

declare(strict_types=1);

namespace App\Payment\Application\Dto;

class PaymentProcessingResponseDto extends AbstractBaseDto
{
    public function __construct(
        public readonly string $transactionId,
        public readonly string $createdAt,
        public readonly int    $amount,
        public readonly string $currency,
        public readonly string $cardBin
    )
    {
    }
}
