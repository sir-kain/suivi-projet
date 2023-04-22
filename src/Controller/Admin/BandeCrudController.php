<?php

namespace App\Controller\Admin;

use App\Entity\Bande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class BandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('date_debut'),
            IntegerField::new('nb_poussins'),
            IntegerField::new('nb_jours')->hideOnForm(),
            IntegerField::new('nb_mortalite'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $addDepense = Action::new('Ajouter depense', 'Invoice', 'fa fa-file-invoice')
            ->linkToUrl('http://127.0.0.1:8000/admin?crudAction=new&crudControllerFqcn=App\Controller\Admin\DepenseCrudController&referrer=http://127.0.0.1:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CDepenseCrudController');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_DETAIL, $addDepense);
    }
}
