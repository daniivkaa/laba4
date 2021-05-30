<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopAdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
		// redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ProductCrudController::class)->generateUrl());
    }
	
	public function configureMenuItems(): iterable
	{
		yield MenuItem::section('Магазин');
		yield MenuItem::linkToCrud('Product', 'fa fa-file-pdf', Product::class);
		yield MenuItem::linkToCrud('Comment', 'fa fa-file-pdf', Comment::class);
		yield MenuItem::linkToCrud('Category', 'fa fa-file-pdf', Category::class);
		yield MenuItem::section('Пользователи');
		yield MenuItem::linkToCrud('User', 'fa fa-file-pdf', User::class);
	}
}
