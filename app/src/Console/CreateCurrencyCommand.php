<?php

namespace App\Console;

use App\DTO\CurrencyCreationDto;
use App\Service\CurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-currency',
    description: 'Создаёт новую валюту в базе данных.'
)]
class CreateCurrencyCommand extends Command
{
    public function __construct(private readonly CurrencyService $currencyService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('numCode', InputArgument::REQUIRED, 'Цифровой код валюты (например, 840)')
            ->addArgument('charCode', InputArgument::REQUIRED, 'Буквенный код валюты (например, USD)')
            ->addArgument('name', InputArgument::REQUIRED, 'Название валюты (например, Доллар США)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $numCode = $input->getArgument('numCode');
        $charCode = $input->getArgument('charCode');
        $name = $input->getArgument('name');

        // Создаём DTO
        $dto = new CurrencyCreationDto($numCode, $charCode, $name);

        $currency = $this->currencyService->create($dto);

        $output->writeln("✅ Валюта {$charCode} ({$name}) успешно создана с кодом {$numCode}!");

        return Command::SUCCESS;
    }
}
