<?php

namespace App\Form;

use App\Entity\Measurement;
use App\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $locations = $this->locationRepository->findAll();

        $choices = [];
        foreach($locations as $location) {
            $choices[$location->getCity()] = $location;
        }

        $builder
            ->add('weather')
            ->add('celsius', NumberType::class)
            ->add('wind', NumberType::class)
            ->add('humidity', NumberType::class)
            ->add('rain', NumberType::class)
            ->add('created_at', DateTimeType::class, [
                'data' => new \DateTime()
            ])
            ->add('location', ChoiceType::class, [
                'choices' => $choices
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
