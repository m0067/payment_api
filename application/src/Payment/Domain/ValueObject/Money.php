<?php

declare(strict_types=1);

namespace App\Payment\Domain\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

final class Money extends AbstractValueObject
{
    private const CURRENCY_CODE_LENGTH = 3;

    #[Assert\GreaterThan(value: 0)]
    private int $amount;

    #[Assert\NotBlank]
    #[Assert\Length(min: self::CURRENCY_CODE_LENGTH, max: self::CURRENCY_CODE_LENGTH)]
    #[Assert\Currency]
    private string $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAmountInMajorUnits(): float
    {
        return $this->amount / 100;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return sprintf("%s %s", $this->amount, $this->currency);
    }
}