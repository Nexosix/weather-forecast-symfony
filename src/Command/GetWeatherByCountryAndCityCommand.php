<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:country-and-city',
    description: 'Add a short description for your command',
)]
class GetWeatherByCountryAndCityCommand extends Command
{

    public function __construct(LocationRepository $locationRepository, WeatherUtil $weatherService)
    {  
        $this->locationRepository = $locationRepository;
        $this->weatherService = $weatherService;
       
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Country')
            ->addArgument('city', InputArgument::REQUIRED, 'City');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        $data = $this->weatherService->getWeatherByCountryAndCity($country, $city);

        $result = [];
        foreach($data['measurements'] as $measurement) {
            $result[] = [
                'weather'  => $measurement->getWeather(),
                'celsius'  => $measurement->getCelsius(),
                'wind'     => $measurement->getWind(),
                'humidity' => $measurement->getHumidity()
            ];
        }

        $output->writeln(json_encode($result, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
