<?php

declare(strict_types=1);

namespace App\Payment\Domain\Service;

use App\Payment\Domain\Exception\PaymentGatewayException;
use App\Payment\Domain\Exception\PaymentValidationException;
use App\Payment\Domain\ValueObject\Money;
use App\Payment\Domain\ValueObject\PaymentGatewayInfo;

interface PaymentGatewayInterface
{
    /**
     * @param Money $money
     * @param string $number
     * @param string $expMonth
     * @param string $expYear
     * @param string $cvv
     * @return PaymentGatewayInfo
     * @throws PaymentValidationException|PaymentGatewayException
     */
    public function chargeCard(
        Money  $money,
        string $number,
        string $expMonth,
        string $expYear,
        string $cvv
    ): PaymentGatewayInfo;

    public function getName(): string;
}
