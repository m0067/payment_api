<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;

final class PaymentApiCest
{
    public function _before(ApiTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function processPaymentAciSuccess(ApiTester $I): void
    {
        $I->sendPost('/v1/payment/aci', [
            'amount' => 4301,
            'currency' => 'EUR',
            'card_number' => '4200000000000000',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        codecept_debug($response);
    }

    public function processPaymentAciError(ApiTester $I): void
    {
        $I->sendPost('/v1/payment/aci', [
            'amount' => 4301,
            'currency' => 'EUR',
            'card_number' => '4200000000000000asdasd',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        codecept_debug($response);
    }

    public function processPaymentShift4Success(ApiTester $I): void
    {
        $I->sendPost('/v1/payment/shift4', [
            'amount' => 1020,
            'currency' => 'EUR',
            'card_number' => '4200000000000000',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        codecept_debug($response);
    }

    public function processPaymentShift4Error(ApiTester $I): void
    {
        $I->sendPost('/v1/payment/shift4', [
            'amount' => 4301,
            'currency' => 'EUR',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        codecept_debug($response);
    }
}
