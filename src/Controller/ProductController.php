<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
	#[Route('/products/{categorySlug}', name: 'product')]
    public function index(EntityManagerInterface $em, $categorySlug, Request $request, PaginatorInterface $paginator): Response
    {
		$category = $em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);
		$listProducts = $em->getRepository(Product::class)->findBy(['category' => $category], ['created_at' => 'DESC']);
		$allCategory = $em->getRepository(Category::class)->findBy([], ['id' => 'DESC']);
        $products = $paginator->paginate(
            $listProducts,
            $request->query->get('page', 1), 5
        );
        return $this->render('product/index.html.twig', [
			'product'	=>	$products,
			'category'	=>	$allCategory,
        ]);
    }
	
	#[Route('/single/{product}', name: 'singleProduct')]
    public function singleProduct(Product $product)
    {
        return $this->render('product/single.html.twig', [
			'product'	=>	$product,
        ]);
    }
}
