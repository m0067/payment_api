<?php

declare(strict_types=1);

namespace App\Payment\Application\Dto;

abstract class AbstractBaseDto
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
