<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Repository;

use App\Payment\Domain\Entity\PaymentTransaction;
use App\Payment\Domain\Repository\PaymentTransactionRepositoryInterface;

class InMemoryPaymentTransactionRepository implements PaymentTransactionRepositoryInterface
{
    /**
     * @var PaymentTransaction[]
     */
    private array $transactions = [];

    public function save(PaymentTransaction $transaction): void
    {
        $this->transactions[$transaction->getId()->toRfc4122()] = $transaction;
    }

    public function find(string $id): ?PaymentTransaction
    {
        return $this->transactions[$id] ?? null;
    }
}
