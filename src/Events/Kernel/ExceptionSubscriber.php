<?php

namespace App\Events\Kernel;

use App\Exception\ExceptionHasData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionSubscriber
 * @package App\Events\Kernel
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
	/**
	 * @return array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::EXCEPTION => 'exception'
		];
	}

	/**
	 * @param ExceptionEvent $event
	 */
	public function exception(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		$reflection = new \ReflectionClass($exception);

		if ($reflection->isSubclassOf(HttpException::class) || $reflection->getName() === HttpException::class) {
			/** @var HttpException|ExceptionHasData $exception */
			$event->setResponse(
				new JsonResponse(
					array_merge(
						[
							'code' => $exception->getStatusCode()
						],
						$reflection->implementsInterface(ExceptionHasData::class) ? [
							'errors' => $exception->getData()
						] : [
							'message' => $exception->getMessage()
						]
					), $exception->getStatusCode())
			);
		}
	}
}
