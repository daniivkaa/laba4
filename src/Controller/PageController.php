<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
		$listProducts = $em->getRepository(Product::class)->findBy([], ['created_at' => 'DESC']);
		$allCategory = $em->getRepository(Category::class)->findBy([], ['id' => 'DESC']);
        $products = $paginator->paginate(
            $listProducts,
            $request->query->get('page', 1), 5
        );
        return $this->render('page/index.html.twig', [
			'product'	=>	$products,
			'category'	=>	$allCategory,
        ]);
    }
}
