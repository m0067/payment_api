<?php

declare(strict_types=1);

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

final class PaymentFunctionalCest
{
    public function _before(FunctionalTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function processPaymentAciSuccess(FunctionalTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:payment-processing', [
            'name' => 'aci',
            'amount' => 1000,
            'currency' => 'EUR',
            'card_number' => '4200000000000000',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ]);
    }

    public function processPaymentAciError(FunctionalTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:payment-processing', [
            'name' => 'aci',
            'amount' => -1000,
            'currency' => 'EUR',
            'card_number' => '4200000000000000',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ], expectedExitCode: 1);
    }

    public function processPaymentShift4Success(FunctionalTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:payment-processing', [
            'name' => 'shift4',
            'amount' => 1000,
            'currency' => 'EUR',
            'card_number' => '4200000000000000',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ]);
    }

    public function processPaymentShift4Error(FunctionalTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:payment-processing', [
            'name' => 'shift4',
            'amount' => 1000,
            'currency' => 'EUR',
            'card_number' => '',
            'card_exp_month' => '05',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ], expectedExitCode: 1);
    }
}
