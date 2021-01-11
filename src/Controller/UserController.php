<?php

namespace App\Controller;

use App\Entity\User;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use OpenApi\Annotations as OA;

/**
 * Class UserController
 * @package App\Controller
 *
 * @OA\Tag(name="User")
 *
 * @Route("/users")
 */
class UserController extends AbstractController
{
	/**
	 * @Route("", name="users:list", methods={"GET"})
	 * @Security("is_granted('ROLE_ADMIN')")
	 *
	 * @OA\Get(
	 *     @OA\Response(
	 *          response=200,
	 *     		description="Success",
	 *          @OA\JsonContent(
	 *              type="array",
	 *              @OA\Items(ref=@Model(type=User::class, groups={"default", "user:list"}))
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
	public function all(SerializerInterface $serializer): Response
	{
		$em = $this->getDoctrine();

		$users = $em->getRepository(User::class)->findAll();

		return new JsonResponse(
			$serializer->toArray(
				$users,
				SerializationContext::create()->setGroups(['default', 'user:list'])
			)
		);
	}
}
