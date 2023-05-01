<?php

namespace App\Form;

use App\Entity\Vente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite', null, [
                'label' => 'Nombre de poulets:',
            ])
            ->add('prix', null, [
                'label' => 'Prix:',
            ])
            ->add('client', null, [
                'label' => 'Nom du client:',
            ])
            ->add('description', null, [
                'label' => 'Détails supplementaires sur la vente:',
            ])
            ->add('created_at', null, [
                'label' => 'Date de la vente:',
                'widget' => 'single_text',
            ])
            ->add('bande');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vente::class,
        ]);
    }
}
