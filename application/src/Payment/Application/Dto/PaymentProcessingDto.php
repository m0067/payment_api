<?php

declare(strict_types=1);

namespace App\Payment\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PaymentProcessingDto extends AbstractBaseDto
{
    #[Assert\NotBlank]
    public string $paymentGatewayName;

    #[Assert\NotBlank]
    public int $amount;

    #[Assert\NotBlank]
    public string $currency;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{13,19}$/', message: 'Card number must be 13-19 digits')]
    public string $cardNumber;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^(0[1-9]|1[0-2])$/', message: 'Invalid month')]
    public string $cardExpMonth;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{4}$/', message: 'Invalid year')]
    public string $cardExpYear;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{3,4}$/', message: 'CVV must be 3 or 4 digits')]
    public string $cardCvv;

    public function __construct(
        string $paymentGatewayName,
        int    $amount,
        string $currency,
        string $cardNumber,
        string $cardExpMonth,
        string $cardExpYear,
        string $cardCvv,
    )
    {
        $this->paymentGatewayName = $paymentGatewayName;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->cardNumber = $cardNumber;
        $this->cardExpMonth = $cardExpMonth;
        $this->cardExpYear = $cardExpYear;
        $this->cardCvv = $cardCvv;
    }
}
