<?php

namespace App\Controller\Admin;

use App\Entity\Pokemon;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PokemonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pokemon::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('idPokemon')->hideOnForm(),
            TextField::new('nom'),
            IntegerField::new('numeroPokedex'),
            AssociationField::new('type1'),
            AssociationField::new('type2'),
            ImageField::new('imagePrincipale')
                ->setLabel('Image URL or Path'),
            // Add more fields as needed, but this is a good start
        ];
    }
}
