<?php

declare(strict_types=1);

namespace App\Payment\Application\Dto;

class PaymentProcessingResponseDto extends AbstractBaseDto
{
    public function __construct(
        public string $transactionId,
        public string $createdAt,
        public int    $amount,
        public string $currency,
        public string $cardBin
    )
    {
    }
}
