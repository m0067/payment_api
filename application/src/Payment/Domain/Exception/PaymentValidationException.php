<?php

declare(strict_types=1);

namespace App\Payment\Domain\Exception;

use InvalidArgumentException;
use Stringable;

class PaymentValidationException extends InvalidArgumentException
{
    /**
     * @var array<string, string|list<string|Stringable>>
     */
    private array $violations;

    /**
     * @return array<string, string|list<string|Stringable>>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param array<string, string|list<string|Stringable>> $violations
     */
    public function setViolations(array $violations): self
    {
        $this->violations = $violations;

        return $this;
    }
}
