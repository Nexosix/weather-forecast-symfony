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
    name: 'weather:location',
    description: 'Get weather by location',
)]
class GetWeatherByLocationCommand extends Command
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
            ->setName('weather:location')
            ->addArgument('location', InputArgument::REQUIRED, 'Location id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('location');

        $location = $this->locationRepository->findOneById($locationId);
        $measurements = $this->weatherService->getWeatherByLocation($location);       

        $result = [];
        foreach($measurements as $measurement) {
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
