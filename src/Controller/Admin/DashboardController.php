<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;

use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\CommentCrudController;
use App\Controller\Admin\PostCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        
        return $this->redirect($adminUrlGenerator->setController(PostCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Aplicación');
    }

    public function configureMenuItems(): iterable
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');

        yield MenuItem::section('Gestión');

        yield MenuItem::linkToUrl(
            'Categorías', 
            'fas fa-list', 
            $adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl()
        );

        yield MenuItem::linkToUrl(
            'Publicaciones', 
            'fas fa-list', 
            $adminUrlGenerator->setController(PostCrudController::class)->generateUrl()
        );

        yield MenuItem::linkToUrl(
            'Comentarios', 
            'fas fa-list', 
            $adminUrlGenerator->setController(CommentCrudController::class)->generateUrl()
        );

        yield MenuItem::section();
        yield MenuItem::linkToRoute('Sitio Web', 'fa fa-home', 'app_home');
    }
}