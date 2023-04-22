<?php

namespace App\Controller\Admin;

use App\Entity\Depense;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DepenseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Depense::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id')->onlyOnForms(),
            AssociationField::new('bande'),
            TextField::new('description'),
            MoneyField::new('prix')->setCurrency('XOF'),
        ];
    }
}
