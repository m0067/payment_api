<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\PaymentGateway;

use App\Payment\Domain\Exception\PaymentGatewayException;
use App\Payment\Domain\Exception\PaymentValidationException;
use App\Payment\Domain\Service\PaymentGatewayInterface;
use App\Payment\Domain\ValueObject\Money;
use App\Payment\Domain\ValueObject\PaymentGatewayInfo;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class Shift4PaymentGateway implements PaymentGatewayInterface
{
    private const API_BASE_URL = 'https://api.shift4.com';
    private const API_KEY = 'pr_test_tXHm9qV9qV9bjIRHcQr9PLPa';

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /**
     * @param Money $money
     * @param string $number
     * @param string $expMonth
     * @param string $expYear
     * @param string $cvv
     * @return PaymentGatewayInfo
     */
    public function chargeCard(
        Money  $money,
        string $number,
        string $expMonth,
        string $expYear,
        string $cvv
    ): PaymentGatewayInfo
    {
        try {
            $tokenResponse = $this->httpClient->request('POST', self::API_BASE_URL . '/tokens', [
                'auth_basic' => [self::API_KEY, ''],
                'body' => [
                    'number' => $number,
                    'expMonth' => $expMonth,
                    'expYear' => $expYear,
                    'cvc' => $cvv,
                    'cardholderName' => 'Lisa Hold',
                ],
            ]);
            $tokenData = $tokenResponse->toArray(false);

            if ($tokenResponse->getStatusCode() == 400) {
                throw (new PaymentValidationException())->setViolations($tokenData['error']);
            }

            $customerResponse = $this->httpClient->request('POST', self::API_BASE_URL . '/customers', [
                'auth_basic' => [self::API_KEY, ''],
                'body' => [
                    'email' => 'customer@example.com',
                    'card' => $tokenData['id'],
                ],
            ]);
            $customerData = $customerResponse->toArray(false);

            if ($customerResponse->getStatusCode() == 400) {
                throw (new PaymentValidationException())->setViolations($customerData['error']);
            }

            $chargeResponse = $this->httpClient->request('POST', self::API_BASE_URL . '/charges', [
                'auth_basic' => [self::API_KEY, ''],
                'body' => [
                    'amount' => $money->getAmount(),
                    'currency' => $money->getCurrency(),
                    'customerId' => $customerData['id'],
                ],
            ]);
            $chargeData = $chargeResponse->toArray(false);

            if ($chargeResponse->getStatusCode() == 400) {
                throw (new PaymentValidationException())->setViolations($chargeData['error']);
            }
        } catch (PaymentValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new PaymentGatewayException('Shift4 payment request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return new PaymentGatewayInfo(
            $chargeData['id'],
            self::getName()
        );
    }

    public static function getName(): string
    {
        return 'shift4';
    }
}
