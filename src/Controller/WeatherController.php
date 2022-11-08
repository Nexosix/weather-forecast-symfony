<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends AbstractController
{

    public function locationAction(Location $location, WeatherUtil $weatherService): Response
    {
        $measurements = $weatherService->getWeatherByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

    public function cityAction(string $country, string $city, WeatherUtil $weatherService): Response
    {
        $result = $weatherService->getWeatherByCountryAndCity($country, $city);
        return $this->render('weather/city.html.twig', $result);
    }
}
