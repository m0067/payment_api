<?php

declare(strict_types=1);

namespace App\Infrastructure\PaymentGateway\Aci;

namespace App\Payment\Infrastructure\PaymentGateway;

use App\Payment\Domain\Exception\PaymentGatewayException;
use App\Payment\Domain\Exception\PaymentValidationException;
use App\Payment\Domain\Service\PaymentGatewayInterface;
use App\Payment\Domain\ValueObject\Money;
use App\Payment\Domain\ValueObject\PaymentGatewayInfo;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class AciPaymentGateway implements PaymentGatewayInterface
{
    private const API_URL = 'https://eu-test.oppwa.com/v1/payments';
    private const AUTH_TOKEN = 'OGFjN2E0Yzc5Mzk0YmRjODAxOTM5NzM2ZjFhNzA2NDF8enlac1lYckc4QXk6bjYzI1NHNng=';

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function chargeCard(
        Money  $money,
        string $number,
        string $expMonth,
        string $expYear,
        string $cvv
    ): PaymentGatewayInfo
    {
        $form = [
            'entityId' => '8ac7a4c79394bdc801939736f17e063d',
            'amount' => $money->getAmountInMajorUnits(),
            'currency' => $money->getCurrency(),
            'paymentBrand' => 'VISA',
            'paymentType' => 'DB',
            'card.holder' => 'Jane Jones',
            'card.number' => $number,
            'card.expiryMonth' => $expMonth,
            'card.expiryYear' => $expYear,
            'card.cvv' => $cvv,
        ];

        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::AUTH_TOKEN,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => http_build_query($form),
                'verify_peer' => false
            ]);
            $content = $response->toArray(false);

            if ($response->getStatusCode() === 400) {
                throw (new PaymentValidationException())->setViolations($content['result']['parameterErrors']);
            }
        } catch (PaymentValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new PaymentGatewayException('Aci payment request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return new PaymentGatewayInfo(
            $content['id'],
            self::getName()
        );
    }

    public static function getName(): string
    {
        return 'aci';
    }
}
