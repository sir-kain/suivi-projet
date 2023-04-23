<?php

namespace App\Form;

use App\Entity\Bande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_poussins', null, [
                'label' => 'Nombre de poussins'
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date démarrage',
                'widget' => 'single_text',
            ])
            ->add('nb_mortalite', null, [
                'label' => 'Mortalités'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bande::class,
        ]);
    }
}
