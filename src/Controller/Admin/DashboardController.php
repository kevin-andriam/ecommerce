<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Controller\Admin\ProductCrudController;
use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\OrderCrudController;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('🛒 Erica & Kevin\'s shop')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', null);
        yield MenuItem::section('Catalogue');
        yield MenuItem::linkTo('Produits', null, ProductCrudController::class)->setAction('index');
        yield MenuItem::linkTo('Catégories', null, CategoryCrudController::class)->setAction('index');
        yield MenuItem::section('Ventes');
        yield MenuItem::linkTo('Commandes', null, OrderCrudController::class)->setAction('index');
        yield MenuItem::linkTo('Utilisateurs', null, UserCrudController::class)->setAction('index');
        yield MenuItem::section('');
        yield MenuItem::linkToRoute('← Retour au site', null, 'home');
        yield MenuItem::linkToRoute('Déconnexion', null, 'app_logout');
    }
}