<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Console\Command;

use App\Payment\Application\Dto\PaymentProcessingDto;
use App\Payment\Application\UseCase\PaymentProcessingUseCase;
use App\Payment\Domain\Exception\PaymentValidationException;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:payment-processing',
    description: 'Processes a payment',
)]
class PaymentProcessingCommand extends Command
{
    public function __construct(
        private readonly PaymentProcessingUseCase $paymentProcessingUseCase
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Gateway name (e.g., shift4)')
            ->addArgument('amount', InputArgument::REQUIRED, 'Payment amount in minor units (e.g., 9999 = 99.99)')
            ->addArgument('currency', InputArgument::REQUIRED, 'Currency code (e.g., USD)')
            ->addArgument('card_number', InputArgument::REQUIRED, 'Card number (e.g., 4242424242424242)')
            ->addArgument('card_exp_month', InputArgument::REQUIRED, 'Expiration month (MM)')
            ->addArgument('card_exp_year', InputArgument::REQUIRED, 'Expiration year (YYYY)')
            ->addArgument('card_cvv', InputArgument::REQUIRED, 'Card CVV (e.g., 123)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $amount = $input->getArgument('amount');
        $currency = $input->getArgument('currency');
        $cardNumber = $input->getArgument('card_number');
        $cardExpMonth = $input->getArgument('card_exp_month');
        $cardExpYear = $input->getArgument('card_exp_year');
        $cardCvv = $input->getArgument('card_cvv');

        if (
            !is_string($name) ||
            !is_numeric($amount) ||
            !is_string($currency) ||
            !is_string($cardNumber) ||
            !is_string($cardExpMonth) ||
            !is_string($cardExpYear) ||
            !is_string($cardCvv)
        ) {
            throw new InvalidArgumentException('Invalid arguments passed to the command.');
        }

        $io = new SymfonyStyle($input, $output);

        $dto = new PaymentProcessingDto(
            $name,
            (int)$amount,
            $currency,
            $cardNumber,
            $cardExpMonth,
            $cardExpYear,
            $cardCvv,
        );

        try {
            $result = $this->paymentProcessingUseCase->execute($dto);
            $io->success('Payment processed successfully.');
            $io->writeln((string)json_encode($result->toArray(), JSON_PRETTY_PRINT));

            return Command::SUCCESS;
        } catch (PaymentValidationException $e) {
            $io->error('Validation failed:');

            foreach ($e->getViolations() as $violation) {
                $io->writeln($violation);
            }

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $io->error('Unexpected error: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
