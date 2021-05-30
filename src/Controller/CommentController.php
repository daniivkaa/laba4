<?php

namespace App\Controller;

use App\Service\RatingProductService;
use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{product}', name: 'createCommentForm')]
    public function createComment(Product $product, EntityManagerInterface $em, Request $request): Response
    {
		$comment = new Comment();
		
		$form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('createCommentForm', [
                'product' => $product->getId()
            ]),
            'method' => 'POST',
        ]);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getUser();
			
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setProduct($product);
			$comment->setUser($user);

            $em->persist($comment);
            $em->flush();
			
			RatingProductService::setProductRating($em, $product);

            return $this->redirectToRoute('singleProduct', ['product' => $product->getId()]);
        }
		
        return $this->render('comment/form.html.twig', [
            'form' => $form->createView(),
			'product' => $product
        ]);
    }
}
