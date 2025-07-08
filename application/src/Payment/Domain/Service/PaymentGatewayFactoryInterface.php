<?php

declare(strict_types=1);

namespace App\Payment\Domain\Service;

use App\Payment\Domain\Exception\PaymentValidationException;

interface PaymentGatewayFactoryInterface
{
    /**
     * @param string $name
     * @return PaymentGatewayInterface
     * @throws PaymentValidationException
     */
    public function create(string $name): PaymentGatewayInterface;
}
