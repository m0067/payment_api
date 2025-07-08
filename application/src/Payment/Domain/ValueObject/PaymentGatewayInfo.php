<?php

declare(strict_types=1);

namespace App\Payment\Domain\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

final class PaymentGatewayInfo extends AbstractValueObject
{
    private string $externalTransactionId;

    #[Assert\Length(min: 2, max: 50)]
    private string $name;

    public function __construct(string $externalTransactionId, string $name)
    {
        $this->externalTransactionId = $externalTransactionId;
        $this->name = $name;
    }

    public function getExternalTransactionId(): string
    {
        return $this->externalTransactionId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->externalTransactionId;
    }
}
