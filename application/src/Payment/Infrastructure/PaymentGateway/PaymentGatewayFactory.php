<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\PaymentGateway;

use App\Payment\Domain\Exception\PaymentValidationException;
use App\Payment\Domain\Service\PaymentGatewayFactoryInterface;
use App\Payment\Domain\Service\PaymentGatewayInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaymentGatewayFactory implements PaymentGatewayFactoryInterface
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /**
     * @param string $name
     * @return PaymentGatewayInterface
     * @throws PaymentValidationException
     */
    public function create(string $name): PaymentGatewayInterface
    {
        return match ($name) {
            Shift4PaymentGateway::getName() => new Shift4PaymentGateway($this->httpClient),
            AciPaymentGateway::getName() => new AciPaymentGateway($this->httpClient),
            default => throw (new PaymentValidationException())->setViolations(['name' => 'Invalid payment gateway.']),
        };
    }
}
