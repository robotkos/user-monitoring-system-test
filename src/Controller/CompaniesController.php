<?php

namespace App\Controller;

use App\Entity\CompaniesVPN;
use App\Entity\UsersVPS;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

class CompaniesController extends AbstractController
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
     * @SWG\Tag(name="Companies")
     * @Security(name="Bearer")
     */

    public function companiesList()
    {
        $serializer = $this->get('serializer');
        $em = $this->em->getRepository(CompaniesVPN::class)->findAll();
        $json = $serializer->serialize($em, 'json');
        return new JsonResponse($json, 200);
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
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of Company"
     * )
     * @SWG\Parameter(
     *     name="quota",
     *     in="query",
     *     type="string",
     *     description="The quota of Company"
     * )
     * @SWG\Tag(name="Companies")
     * @Security(name="Bearer")
     */

    public function companiesAdd(Request $request)
    {
        $name = $request->query->get('name');
        $quota = $request->query->get('quota');

        if (!empty($name) && !empty($quota)){
            $em = $this->em;
            $user = new CompaniesVPN();
            $user->setName($name);
            $user->setQuota($quota);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['message' => 'Saved'], 200);
        }
        return new JsonResponse(['message' => 'Something went wrong!'], 500);
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
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The id of Company"
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of Company"
     * )
     * @SWG\Parameter(
     *     name="quota",
     *     in="query",
     *     type="string",
     *     description="The email of Company"
     * )
     * @SWG\Tag(name="Companies")
     * @Security(name="Bearer")
     */

    public function companiesEdit(Request $request)
    {
        $id = $request->query->get('id');
        $name = $request->query->get('name');
        $quota = $request->query->get('quota');

        if (!empty($name) && !empty($quota) && !empty($id)){
            $em = $this->em;
            $emUser = $em->getRepository(CompaniesVPN::class);
            $userData = $emUser->findOneBy(
                [
                    'id' => $id
                ]
            );
            if (isset($userData)){
                $userData->setName($name);
                $userData->setQuota($quota);
                $em->persist($userData);
                $em->flush();
                return new JsonResponse(['message' => 'Saved'], 200);
            }
        }
        return new JsonResponse(['message' => 'Something went wrong!'], 500);
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
     * @SWG\Tag(name="Companies")
     * @Security(name="Bearer")
     */

    public function companiesDelete($id)
    {
        if (!empty($id)){
            $em = $this->em;
            $emUser = $em->getRepository(CompaniesVPN::class);
            $userData = $emUser->findOneBy(
                [
                    'id' => $id
                ]
            );
            if (isset($userData)){
                $em->remove($userData);
                $em->flush();
                return new JsonResponse(['message' => 'Removed'], 200);
            }
        }
        return new JsonResponse(['message' => 'Something went wrong!'], 500);
    }
}
