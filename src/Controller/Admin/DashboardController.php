<?php

namespace App\Controller\Admin;

use App\Entity\Dresseur;
use App\Entity\Team;
use App\Entity\Pokemon;
use App\Entity\Arene;
use App\Entity\Type;
use App\Entity\Organisation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<div class="custom-admin-logo">PokeWorld</div>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Gestion');
        yield MenuItem::linkToCrud('Dresseurs', 'fas fa-user', Dresseur::class);
        yield MenuItem::linkToCrud('Teams', 'fas fa-users', Team::class);
        yield MenuItem::linkToCrud('Arènes', 'fas fa-building', Arene::class);
        yield MenuItem::linkToCrud('Organisations', 'fas fa-user-secret', Organisation::class);
    }

    public function configureAssets(): \EasyCorp\Bundle\EasyAdminBundle\Config\Assets
    {
        return \EasyCorp\Bundle\EasyAdminBundle\Config\Assets::new()
            ->addCssFile('styles/admin.css');
    }
}
