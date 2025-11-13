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

        // давай представим что уже есть в БД запись
        // id|num_code|char_code|name|
        // --+--------+---------+----+
        //  1| 840    | USD     | Доллар
        // и админ через консоль вводит опять эту же валюту
        // php bin/console numCode=840 charCode='USD' name='Доллар'
        // и произойдет ошибка на уникальные ключи, потому что уже есть эти numCode и charCode
        // что надо сделать надо удостовериться что в БД нету этих данных через методы
        // \App\Repository\CurrencyRepository::findByNumCode
        // \App\Repository\CurrencyRepository::findByCharCode
        // и получается что ты пишешь тот же код, который уже есть в
        // \App\Service\CurrencyService::create
        // поэтому тут тебе надо вызвать \App\Service\CurrencyService::create

        $currency = new Currency($numCode, $charCode, $name);

        $this->em->persist($currency);
        $this->em->flush();

        $output->writeln("✅ Валюта {$charCode} ({$name}) успешно создана с кодом {$numCode}!");

        return Command::SUCCESS;
    }
}
