<?php

namespace App\Console;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
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

        $currency = new Currency($numCode, $charCode, $name);

        $this->em->persist($currency);
        $this->em->flush();

        $output->writeln("✅ Валюта {$charCode} ({$name}) успешно создана с кодом {$numCode}!");

        return Command::SUCCESS;
    }
}
