<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

class UserController extends AbstractController
{

    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/user", methods={"GET"})
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
     * @SWG\Tag(name="rewards")
     * @Security(name="Bearer")
     */

    public function index()
    {

        return Response::create('text');
    }
}
