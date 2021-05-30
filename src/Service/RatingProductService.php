<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class RatingProductService
{
    static public function setProductRating(EntityManagerInterface $em, Product $product)
    {
		$rating = 0;
		
		$comments = $em->getRepository(Comment::class)->findBy(['product' => $product]);
		foreach($comments as $commentItem){
			$rating += $commentItem->getRating();
		}
		$rating = round($rating / count($comments), 1);
		$product->setRating($rating);
		$em->persist($product);
		$em->flush();
    }
}