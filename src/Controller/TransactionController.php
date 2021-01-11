<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Exception\FormException;
use App\Form\Transaction\CreateTransactionType;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use OpenApi\Annotations as OA;

/**
 * Class TransactionController
 * @package App\Controller
 *
 * @OA\Tag(name="Transaction")
 *
 * @Route("/transactions")
 */
class TransactionController extends AbstractController
{
	/**
	 * @Route("", name="transactions:create", methods={"POST"})
	 * @Security("is_granted('ROLE_USER')")
	 *
	 * @OA\Post(
	 *     @OA\RequestBody(
	 *     		@OA\JsonContent(ref=@Model(type=CreateTransactionType::class))
	 *     ),
	 *     @OA\Response(
	 *          response=200,
	 *     		description="Success",
	 *          @OA\JsonContent(
	 *              type="object",
	 *              ref=@Model(type=Transaction::class, groups={"default", "transaction:create"})
	 *          )
	 *     ),
	 *     @OA\Response(
	 *          response=403,
	 *          description="Forbiden"
	 *     )
	 * )
	 *
	 * @param SerializerInterface|Serializer $serializer
	 * @return Response
	 */
	public function create(Request $request, SerializerInterface $serializer): Response
	{
		$em = $this->getDoctrine();

		$entity = (new Transaction())->setSender($this->getUser());

		$form = $this->createForm(CreateTransactionType::class, $entity);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em->getManager()->persist($entity);
			$em->getManager()->flush();

			return new JsonResponse(
				$serializer->toArray(
					$entity,
					SerializationContext::create()->setGroups(['default', 'transaction:create'])
				)
			);
		}

		throw new FormException($form);
	}
}
