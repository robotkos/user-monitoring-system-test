<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

class CompaniesController extends AbstractController
{

    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Rest\Route("/api/companies/list", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     * )
     * @SWG\Tag(name="Companies")
     * @Security(name="Bearer")
     */

    public function index()
    {
        return $this->render('companies/index.html.twig', [
            'controller_name' => 'CompaniesController',
        ]);
    }
}
