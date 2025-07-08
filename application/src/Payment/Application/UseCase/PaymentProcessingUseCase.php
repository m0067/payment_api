<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase;

use App\Payment\Application\Dto\PaymentProcessingDto;
use App\Payment\Application\Dto\PaymentProcessingResponseDto;
use App\Payment\Domain\Exception\PaymentGatewayException;
use App\Payment\Domain\Exception\PaymentValidationException;
use App\Payment\Domain\Service\PaymentProcessor;
use App\Payment\Domain\ValueObject\Money;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentProcessingUseCase
{
    public function __construct(private PaymentProcessor $paymentProcessor, private ValidatorInterface $validator)
    {
    }

    /**
     * @param PaymentProcessingDto $dto
     * @return PaymentProcessingResponseDto
     * @throws PaymentGatewayException
     * @throws PaymentValidationException
     */
    public function execute(PaymentProcessingDto $dto): PaymentProcessingResponseDto
    {
        try {
            $errors = $this->validator->validate($dto);

            if (count($errors) > 0) {
                $violations = [];

                foreach ($errors as $error) {
                    $propertyPath = $error->getPropertyPath();
                    $message = $error->getMessage();
                    $violations[$propertyPath][] = $message;
                }

                throw (new PaymentValidationException())->setViolations($violations);
            }

            $paymentResponse = $this->paymentProcessor->process(
                new Money($dto->amount, $dto->currency),
                $dto->cardNumber,
                $dto->cardExpMonth,
                $dto->cardExpYear,
                $dto->cardCvv,
                $dto->paymentGatewayName
            );

            return new PaymentProcessingResponseDto(
                $paymentResponse->getId()->toRfc4122(),
                $paymentResponse->getCreatedAt()->format(\DateTimeInterface::ATOM),
                $paymentResponse->getMoney()->getAmount(),
                $paymentResponse->getMoney()->getCurrency(),
                $paymentResponse->getCardBin()->getBin()
            );
        } catch (PaymentGatewayException $e) {
            //TODO
            throw $e;
        }
    }
}
