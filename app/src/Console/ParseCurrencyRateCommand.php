<?php

namespace App\Console;

use App\DTO\CurrencyRateCreationDto;
use App\Entity\CurrencyRate;
use App\Factory\ParseFactory;
use App\HttpClient\CurrencyRateHttpClient;
use App\Repository\CurrencyRateRepository;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyRateService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function sprintf;

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
        private CurrencyRepository $currencyRepository,
        private CurrencyRateRepository $currencyRateRepository,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $dto = $this->client->request();
            $parser = $this->parseFactory->create($dto->contentType);
            $currencyRatesData = $parser->parse($dto);

            // сделать запрос в БД и получить все currency! какие есть в БД.
            // если админ добавил USD и EUR только, то отсортировать $currencyRatesData
            // так чтобы в нем были только USD и EUR и эти данные записать в БД - CurrencyRate
            // и дописать JsonParser использую команду json_decode()

            // Получаем все валюты, которые есть в базе данных
            $currencies = $this->currencyRepository->findAllCurrencies();
            $result = [];
            $dateParser = new DateTimeImmutable();
            foreach ($currencies as $currency) {
                foreach ($currencyRatesData as $currencyRateItem) {
                    if (
                        $currencyRateItem->charCode === $currency->getCharCode()
                        && $currencyRateItem->numCode === $currency->getNumCode()
                    ) {
                        // clean architecture - martin
                        // ddd - usecase/handler/executor.

                        $currencyRate = new CurrencyRate($currency, $currencyRateItem->value, $dateParser);
                        $this->currencyRateRepository->add($currencyRate);

                        $result[] = $currencyRate;
                    }
                }
            }
            $this->em->flush();

            $this->showInfo($output, $result);

            $output->writeln('Parser finished.');
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @param CurrencyRate[] $currencyRates
     */
    private function showInfo(OutputInterface $output, array $currencyRates): void
    {
        foreach ($currencyRates as $currencyRate) {
            $msg = sprintf(
                'was added valueId: %d, for currency:(numCode: %s, charCode: %s)',
                $currencyRate->getId(),
                $currencyRate->getCurrency()->getNumCode(),
                $currencyRate->getCurrency()->getCharCode(),
            );
            $output->writeln($msg);

            $this->logger->info($msg, ['currency' => $currencyRate->getCurrency(), 'currencyRate' => $currencyRate]);
        }
    }
}
