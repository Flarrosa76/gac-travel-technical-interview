<?php
namespace App\Subscribers;

use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Entity\Users;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

class StockSubscrber implements EventSubscriber
{
	private $security;


	public function __construct(Security $security)
    {
        $this->security = $security;

    }
	public function getSubscribedEvents(){

		return [
			Events::postUpdate,
		];

	}

	public function postUpdate(LifecycleEventArgs $args)
	{
		$em = $args->getEntityManager();
		$entidad = $args->getEntity();

		if ($entidad instanceof Products) {
			$uow = $em->getUnitOfWork();
			$changes_set = $uow->getEntityChangeSet($entidad);
			if(!empty($changes_set['stock'])){
				$variacion = $changes_set['stock'][0]-$changes_set['stock'][1];
				/** @var Users $user */
				$user = $this->security->getUser();
				if(!empty($user)){
					$userId = $user->getId();
				}
				$movimiento = new StockHistoric();
				$movimiento->setProduct($entidad);
				$movimiento->setUser($user);
				$movimiento->setStock($variacion);

				$em->persist($movimiento);
				$em->flush($movimiento);
				
			}

		}								
	}

}
