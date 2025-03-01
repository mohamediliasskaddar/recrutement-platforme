<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du poste'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'offre'
            ])
            ->add('salaire', TextType::class, [
                'label'    => 'Salaire',
                'required' => false,
            ])
            ->add('localisation', TextType::class, [
                'label'    => 'Localisation',
                'required' => false,
            ])
            ->add('typeContrat', TextType::class, [
                'label'    => 'Type de contrat',
                'required' => false,
            ])
            ->add('dateFin', DateTimeType::class, [
                'label'    => 'Date de fin',
                'widget'   => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
