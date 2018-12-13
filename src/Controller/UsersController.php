<?php

namespace App\Controller;

use App\Entity\UsersVPS;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

class UsersController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     * )
     * @SWG\Tag(name="Users")
     * @Security(name="Bearer")
     */

    public function usersList()
    {
        $serializer = $this->get('serializer');
        $em = $this->em->getRepository(UsersVPS::class)->findAll();
        $json = $serializer->serialize($em, 'json');
        return new JsonResponse($json, 200);
    }

    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Rest\Route("/api/users/add", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
     * )
     * @SWG\Tag(name="Users")
     * @Security(name="Bearer")
     */

    public function usersAdd()
    {

        $message = [
            'test' => 'yes'
        ];
        return new JsonResponse([], 200);
    }
}
