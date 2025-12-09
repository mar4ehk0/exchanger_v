<?php

namespace App\Console;

use App\Factory\ParseFactory;
use App\HttpClient\CurrencyRateHttpClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function dd;

#[AsCommand(
    name: 'app:parse-currency-rate',
    description: 'Парсит значение валюты.'
)]
class ParseCurrencyRateCommand extends Command
{
    public function __construct(
        private CurrencyRateHttpClient $client,
        private ParseFactory $parseFactory,
        private LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $dto = $this->client->request();
            $parser = $this->parseFactory->create($dto->contentType);
            $result = $parser->parse($dto);

            // сделать запрос в БД и получить все currency! какие есть в БД.
            // если админ добавил USD и EUR только, то отсортировать $result
            // так чтобы в нем были только USD и EUR и эти данные записать в БД - CurrencyRate
            // и дописать JsonParser использую команду json_decode()

        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return 1;
        }
    }
}
