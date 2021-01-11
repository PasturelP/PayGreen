<?php

namespace App\Events\Kernel;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RequestSubscriber
 * @package App\Events\Kernel
 */
class RequestSubscriber implements EventSubscriberInterface
{
	/**
	 * @return array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::REQUEST => ['request', 9]
		];
	}

	/**
	 * @param RequestEvent $event
	 * @throws HttpException
	 */
	public function request(RequestEvent $event): void
	{
		$request = $event->getRequest();

		$content_type = explode(";", $request->headers->get("content-type"))[0];

		if ($content_type === "application/json") {
			$content = $request->getContent();

			if (!empty($content)) {
				$content = json_decode($content, true);
				if (in_array($content, ['', null, false])) {
					throw new BadRequestHttpException();
				}
				$request->request->replace($content);
			}
		}
	}
}
