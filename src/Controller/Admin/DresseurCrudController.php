<?php

namespace App\Controller\Admin;

use App\Entity\Dresseur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Controller\Admin\EquipeCrudController;
use App\Controller\Admin\TeamCrudController;

class DresseurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dresseur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom')->setRequired(false),
            TextField::new('prenom'),
            ImageField::new('image')
                ->setBasePath('images/')
                ->setUploadDir('public/images/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('villeNatale')->hideOnIndex(),
            TextField::new('region'),
            TextEditorField::new('ambition')->hideOnIndex(),
            TextEditorField::new('description')->hideOnIndex(),
            BooleanField::new('estMechant', 'Antagoniste ?'),
            AssociationField::new('organisation')->setRequired(false),
            TextField::new('grade')->setRequired(false)->hideOnIndex(),
            CollectionField::new('teams')
                ->useEntryCrudForm(TeamCrudController::class)
                ->allowAdd()
                ->allowDelete(),
        ];
    }
}
