<?php

declare(strict_types=1);

namespace App\Payment\Domain\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

final class CardBin extends AbstractValueObject
{
    #[Assert\Length(min: 6, max: 6)]
    private string $number;

    public function __construct(string $number)
    {
        $this->number = substr($number, 0, 6);
    }

    public function getBin(): string
    {
        return $this->number;
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
