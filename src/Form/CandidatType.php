<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomcomplet', TextType::class, [
                'label' => 'Nom complet'
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance'
            ])
            ->add('cnie', TextType::class, [
                'label' => 'CNIE'
            ])
            ->add('sexe', TextType::class, [
                'label' => 'Sexe'
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('competences', TextType::class, [
                'label' => 'Compétences',
                'required' => false,
            ])
            ->add('experiences', TextType::class, [
                'label' => 'Expériences',
                'required' => false,
            ])
            ->add('formations', TextType::class, [
                'label' => 'Formations',
                'required' => false,
            ])
            // Champs pour le fichier CV (non mappé à l'entité)
            ->add('cvFile', FileType::class, [
            'label'    => 'CV (PDF, DOC, DOCX...)',
            'mapped'   => false,
            'required' => false,
            ])
            // Champs pour la photo (non mappé)
            ->add('imageFile', FileType::class, [
                'label'    => 'Photo personnelle (JPEG, PNG...)',
                'mapped'   => false,
                'required' => false,
            ]);
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
