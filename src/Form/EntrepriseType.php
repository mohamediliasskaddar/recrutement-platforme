<?php
namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEntreprise', TextType::class, [
                'label' => 'Nom de l\'entreprise'
            ])
            ->add('description', TextareaType::class, [
                'label'    => 'Description',
                'required' => false,
            ])
            ->add('secteurs', TextareaType::class, [
                'label'    => 'Secteurs d\'activité',
                'required' => false,
            ])
            ->add('siteWeb', TextType::class, [
                'label'    => 'Site Web',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
