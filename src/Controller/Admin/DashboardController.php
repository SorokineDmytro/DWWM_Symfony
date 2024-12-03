<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Tiers;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\TypeTiers;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();
        return $this->render("admin/admin.html.twig");

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dwwm');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categorie', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Produit', 'fas fa-box-archive', Produit::class);
        yield MenuItem::subMenu('Tiers', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Ajout Tiers', 'fas fa-user-plus',Tiers::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste des Tiers', 'fas fa-users',Tiers::class),
            MenuItem::linkToCrud('Ajout Type Tiers', 'fas fa-user-gear', TypeTiers::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste Type Tiers', 'fas fa-users-gear', TypeTiers::class),
        ]);
        yield MenuItem::subMenu('User', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Ajout User', 'fas fa-edit',User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste des User', 'fas fa-list',User::class),
            MenuItem::linkToCrud('Ajout Role', 'fas fa-user-edit', Role::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste Role', 'fas fa-list', Role::class),
        ]);
        yield MenuItem::linkToRoute("Retour à l'accueil", 'fas fa-backward', 'app_accueil');
    }
}
