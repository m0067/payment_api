<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Http\Controller\Api\V1;

use App\Payment\Application\Dto\PaymentProcessingDto;
use App\Payment\Application\UseCase\PaymentProcessingUseCase;
use App\Payment\Domain\Exception\PaymentValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PaymentController extends AbstractController
{
    public function __construct(private PaymentProcessingUseCase $paymentProcessingUseCase)
    {

    }

    #[Route('/api/v1/payment/{name}', name: 'api_v1_payment_processing', methods: ['POST'])]
    public function processing(string $name, Request $request): JsonResponse
    {
        try {
            $dto = new PaymentProcessingDto(
                $name,
                $request->request->getInt('amount'),
                $request->request->getString('currency'),
                $request->request->getString('card_number'),
                $request->request->getString('card_exp_month'),
                $request->request->getString('card_exp_year'),
                $request->request->getString('card_cvv')
            );
            $result = $this->paymentProcessingUseCase->execute($dto);

            return $this->json($result->toArray());
        } catch (PaymentValidationException $e) {
            return $this->json([
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $e->getViolations()
            ], 400);
        }
    }
}
