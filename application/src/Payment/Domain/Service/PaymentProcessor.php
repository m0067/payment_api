<?php

declare(strict_types=1);

namespace App\Payment\Domain\Service;

use App\Payment\Domain\Entity\PaymentTransaction;
use App\Payment\Domain\Exception\PaymentGatewayException;
use App\Payment\Domain\Exception\PaymentValidationException;
use App\Payment\Domain\Repository\PaymentTransactionRepositoryInterface;
use App\Payment\Domain\ValueObject\CardBin;
use App\Payment\Domain\ValueObject\Money;
use App\Payment\Infrastructure\PaymentGateway\PaymentGatewayFactory;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class PaymentProcessor
{
    public function __construct(
        private PaymentGatewayFactory                 $paymentGatewayFactory,
        private ValidatorInterface                    $validator,
        private PaymentTransactionRepositoryInterface $paymentTransactionRepository
    )
    {
    }

    /**
     * @param Money $money
     * @param string $number
     * @param string $expMonth
     * @param string $expYear
     * @param string $cvv
     * @param string $gatewayName
     * @return PaymentTransaction
     * @throws PaymentValidationException|PaymentGatewayException|Throwable
     */
    public function process(
        Money  $money,
        string $number,
        string $expMonth,
        string $expYear,
        string $cvv,
        string $gatewayName
    ): PaymentTransaction
    {
        $errors = $this->validator->validate($money);

        if (count($errors) > 0) {
            $violations = [];

            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                $message = $error->getMessage();
                $violations[$propertyPath][] = $message;
            }

            throw (new PaymentValidationException())->setViolations($violations);
        }

        $paymentGateway = $this->paymentGatewayFactory->create($gatewayName);
        $transaction = new PaymentTransaction(Uuid::v7(), $money, new CardBin($number));

        try {
            $this->paymentTransactionRepository->save($transaction);
            $info = $paymentGateway->chargeCard($money, $number, $expMonth, $expYear, $cvv);
            $transaction->setPaymentGatewayInfo($info);
            $transaction->markAsSucceeded();
            $this->paymentTransactionRepository->save($transaction);
        } catch (Throwable $e) {
            $transaction->markAsFailed();
            $this->paymentTransactionRepository->save($transaction);
            throw $e;
        }

        return $transaction;
    }
}
