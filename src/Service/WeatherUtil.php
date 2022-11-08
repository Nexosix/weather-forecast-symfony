<?php

namespace App\Service;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtil {
    public function __construct(LocationRepository $locationRepository, MeasurementRepository $measurementRepository) {
        $this->locationRepository = $locationRepository;
        $this->measurementRepository = $measurementRepository;
    }

    public function getWeatherByLocation(Location $location) {
        return $this->measurementRepository->findAllByLocation($location);
    }

    public function getWeatherByCountryAndCity(string $country, string $city) {
        $location = $this->locationRepository->findByCountryAndCity($country, $city);

        return [
            'location' => $location,
            'measurements' => $this->getWeatherByLocation($location)
        ];
    }
}