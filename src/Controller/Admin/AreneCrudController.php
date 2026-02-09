<?php

namespace App\Controller\Admin;

use App\Entity\Arene;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AreneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Arene::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('region'),
            TextField::new('lieu'),
            TextField::new('badge'),
            ImageField::new('imageBadge')
                ->setBasePath('images/')
                ->setUploadDir('public/images/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            AssociationField::new('champion'),
        ];
    }
}
