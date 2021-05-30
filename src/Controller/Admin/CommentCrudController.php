<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\RatingProductService;
use App\Entity\Comment;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use App\Controller\Admin\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
	public function delete(AdminContext $context)
    {
        $event = new BeforeCrudActionEvent($context);
        $this->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        if (!$this->isGranted(Permission::EA_EXECUTE_ACTION)) {
            throw new ForbiddenActionException($context);
        }

        if (!$context->getEntity()->isAccessible()) {
            throw new InsufficientEntityPermissionException($context);
        }

        $csrfToken = $context->getRequest()->request->get('token');
        if (!$this->isCsrfTokenValid('ea-delete', $csrfToken)) {
            return $this->redirectToRoute($context->getDashboardRouteName());
        }

        $entityInstance = $context->getEntity()->getInstance();

        $event = new BeforeEntityDeletedEvent($entityInstance);
        $this->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }
        $entityInstance = $event->getEntityInstance();

        try {
            $this->deleteEntity($this->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $entityInstance);
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new EntityRemoveException(['entity_name' => $context->getEntity()->getName(), 'message' => $e->getMessage()]);
        }

        $this->get('event_dispatcher')->dispatch(new AfterEntityDeletedEvent($entityInstance));

        $responseParameters = $this->configureResponseParameters(KeyValueStore::new([
            'entity' => $context->getEntity(),
        ]));

        $event = new AfterCrudActionEvent($context, $responseParameters);
        $this->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        if (null !== $referrer = $context->getReferrer()) {
            return $this->redirect($referrer);
        }
		
        $em = $this->getDoctrine()->getManager();
		
		$comment = $context->getEntity()->getInstance();
			
		RatingProductService::setProductRating($em, $comment->getProduct());

        return $this->redirectToRoute('admin');
    }
}
