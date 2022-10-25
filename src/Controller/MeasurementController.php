<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Form\MeasurementType;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/measurement')]
class MeasurementController extends AbstractController
{
    #[Route('/', name: 'app_measurement_index', methods: ['GET'])]
    public function index(MeasurementRepository $measurementRepository): Response
    {
        return $this->render('measurement/index.html.twig', [
            'measurements' => $measurementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_measurement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MeasurementRepository $measurementRepository): Response
    {
        $measurement = new Measurement();
        $form = $this->createForm(MeasurementType::class, $measurement, [
            'validation_groups' => ['new', 'edit']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $measurementRepository->save($measurement, true);

            return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('measurement/new.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurement_show', methods: ['GET'])]
    public function show(Measurement $measurement): Response
    {
        return $this->render('measurement/show.html.twig', [
            'measurement' => $measurement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_measurement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        $form = $this->createForm(MeasurementType::class, $measurement, [
            'validation_groups' => ['new', 'edit']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $measurementRepository->save($measurement, true);

            return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('measurement/edit.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[Route('/{country}/{city}', name: 'app_measurement_show_for_city', requirements: ['country' => '\w+', 'city' => '\w+'], methods: ['GET'])]
    public function show_for_city(string $country, string $city, MeasurementRepository $measurementRepository, LocationRepository $locationRepository): Response
    {
        $location = $locationRepository->findByCountryAndCity($country, $city);
        $measurements = $measurementRepository->findAllByLocation($location);
        return $this->render('measurement/show_for_city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

    #[Route('/{id}', name: 'app_measurement_delete', methods: ['POST'])]
    public function delete(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measurement->getId(), $request->request->get('_token'))) {
            $measurementRepository->remove($measurement, true);
        }

        return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
    }
}
