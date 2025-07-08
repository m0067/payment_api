<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Payment\Domain\ValueObject\CardBin;
use App\Payment\Domain\ValueObject\Money;
use App\Payment\Domain\ValueObject\PaymentGatewayInfo;
use App\Payment\Domain\ValueObject\PaymentTransactionStatus;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class PaymentTransaction
{
    private Uuid $id;
    private Money $money;
    private ?PaymentGatewayInfo $paymentGatewayInfo;
    private PaymentTransactionStatus $status;
    private CardBin $cardBin;
    private DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, Money $money, CardBin $cardBin)
    {
        $this->id = $id;
        $this->money = $money;
        $this->cardBin = $cardBin;
        $this->createdAt = new DateTimeImmutable();
        $this->status = new PaymentTransactionStatus(PaymentTransactionStatus::STATUS_PENDING);
        $this->paymentGatewayInfo = null;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getCardBin(): CardBin
    {
        return $this->cardBin;
    }

    public function setPaymentGatewayInfo(PaymentGatewayInfo $paymentGatewayInfo): self
    {
        $this->paymentGatewayInfo = $paymentGatewayInfo;

        return $this;
    }

    public function getPaymentGatewayInfo(): ?PaymentGatewayInfo
    {
        return $this->paymentGatewayInfo;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStatus(): PaymentTransactionStatus
    {
        return $this->status;
    }

    public function markAsSucceeded(): void
    {
        $this->status = new PaymentTransactionStatus(PaymentTransactionStatus::STATUS_SUCCESS);
    }

    public function markAsFailed(): void
    {
        $this->status = new PaymentTransactionStatus(PaymentTransactionStatus::STATUS_FAILED);
    }
}
