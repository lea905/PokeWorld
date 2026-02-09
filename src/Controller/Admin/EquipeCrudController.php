<?php

namespace App\Controller\Admin;

use App\Entity\Equipe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class EquipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('pokemon')
                ->setRequired(true)
                ->autocomplete(), // Useful if many Pokemon
            IntegerField::new('niveau'),
            AssociationField::new('dresseur')
                ->setRequired(true)
                ->hideOnForm(), // Hidden because we usually add teams FROM the Dresseur form
        ];
    }
}
