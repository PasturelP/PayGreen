<?php

namespace App\Events\ORM;

use App\Entity\Fields\HasUuid;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Uid\Uuid;

/**
 * Class UuidSubscriber
 * @package App\Events\ORM
 */
class UuidSubscriber implements EventSubscriber
{
	/**
	 * @return array
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::prePersist
		];
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$object = $args->getObject();
		if (
			in_array(HasUuid::class, (new \ReflectionClass($object))->getTraitNames()) &&
			$object->getUuid() === null
		) {
			$object->setUuid(Uuid::v4());
		}
	}

}
