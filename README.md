# Payment Api App

Domain-Driven Design (DDD) implementation of a Payment API built with the Symfony Framework, supporting ACI and Shift4 as payment gateways. The system follows clean architecture principles with clearly separated application, domain, infrastructure and presentation layers.

It is assumed that you are using a Linux system with Docker and docker-compose installed.

## Init project
Run

```bash
./init.sh
```

## Check with Cli
Run

```bash
docker-compose exec payment_api_php php bin/console app:payment-processing shift4 9900 USD 4242424242424242 12 2028 123
```

## Check with Curl
Run

```bash
curl -X POST http://localhost:10910/api/v1/payment/aci \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "amount=9900" \
  -d "currency=USD" \
  -d "card_number=4242424242424242" \
  -d "card_exp_month=12" \
  -d "card_exp_year=2028" \
  -d "card_cvv=123"
```

## Tests
Run tests

```bash
./run_tests.sh
```

or

```bash
docker-compose exec payment_api_php php vendor/bin/codecept run --steps
```

## PHPStan
Run phpstan

```bash
docker-compose exec payment_api_php composer phpstan
```

### Features:

- Introduced `PaymentTransaction` entity and associated value objects.
- Added `PaymentTransactionRepositoryInterface` with an in-memory implementation.
- Created DTOs for payment processing and infrastructure to support validation.
- Integrated Shift4 and ACI payment gateways with domain service contracts.
- Exposed payment processing functionality through a console command and API endpoint.
- Configured Docker for application environment and testing.
- Set up PHPStan and Codeception for static analysis and testing.
- 