<?php


namespace App\Subscribers;


use App\Entity\Comment;
use App\Entity\Product;
use App\Service\RatingProductService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RatingProductSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityDeletedEvent::class => ['setProductRating']
        ];
    }

    public function setProductRating(AfterEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Comment) {
            RatingProductService::setProductRating($this->em, $entity->getProduct());
        }
    }
}