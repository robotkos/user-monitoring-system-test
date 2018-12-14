<?php

namespace App\Controller;

use App\Entity\CompaniesVPN;
use App\Entity\UsersVPS;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $em = $this->em;
        $serializer = $this->get('serializer');
        $emUsers = $em->getRepository(UsersVPS::class)->findAll();
        $emCompanies = $em->getRepository(CompaniesVPN::class);
        $final = [];
        foreach ($emUsers as $item) {
            $company = $emCompanies->findOneBy([
                'id' => $item->getCompanyId()
            ]);

            $final[] = [
                'companyId' => $item->getCompanyId(),
                'companyName' => $company->getName(),
                'email' => $item->getEmail(),
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }
        $json = $serializer->serialize($final, 'json');
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
     *     description="The name of User"
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The email of User"
     * )
     * @SWG\Parameter(
     *     name="company",
     *     in="query",
     *     type="string",
     *     description="The company of User"
     * )
     * @SWG\Tag(name="Users")
     * @Security(name="Bearer")
     */

    public function usersAdd(Request $request)
    {
        $em = $this->em;
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        if (empty($decodedData)){
            $name = $request->query->get('name');
            $email = $request->query->get('email');
            $companyId = $request->query->get('company');
        } else{
            $name = $decodedData['name'];
            $email = $decodedData['email'];
            $companyId = $decodedData['company'];

        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'Wrong Email format!'], 500);
        }
//        $companyId = $this->getCompanyID($companyName, $em);
        if (!empty($name) && !empty($email) && !empty($companyId)) {
            $user = new UsersVPS();
            $user->setName($name);
            $user->setEmail($email);
            $user->setCompanyId($companyId);
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
     *     description="The id of User"
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of User"
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The email of User"
     * )
     * @SWG\Parameter(
     *     name="company",
     *     in="query",
     *     type="string",
     *     description="The company of User"
     * )
     * @SWG\Tag(name="Users")
     * @Security(name="Bearer")
     */

    public function usersEdit(Request $request)
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        if (empty($decodedData)){
            $id = $request->query->get('id');
            $name = $request->query->get('name');
            $email = $request->query->get('email');
            $companyId = $request->query->get('company');
        } else{
            $id = $decodedData['id'];
            $name = $decodedData['name'];
            $email = $decodedData['email'];
            $companyId = $decodedData['company'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'Wrong Email format!'], 500);
        }

        if (!empty($name) && !empty($email) && !empty($companyId) && !empty($id)) {
            $em = $this->em;
            $emUser = $em->getRepository(UsersVPS::class);
            $userData = $emUser->findOneBy(
                [
                    'id' => $id
                ]
            );
            if (isset($userData)) {
                $userData->setName($name);
                $userData->setEmail($email);
                $userData->setCompanyId($companyId);
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
     * @SWG\Tag(name="Users")
     * @Security(name="Bearer")
     */

    public function usersDelete($id)
    {
        if (!empty($id)) {
            $em = $this->em;
            $emUser = $em->getRepository(UsersVPS::class);
            $userData = $emUser->findOneBy(
                [
                    'id' => $id
                ]
            );
            if (isset($userData)) {
                $em->remove($userData);
                $em->flush();
                return new JsonResponse(['message' => 'Removed'], 200);
            }
        }
        return new JsonResponse(['message' => 'Something went wrong!'], 500);
    }

    private function getCompanyID($name, EntityManagerInterface $em){
        $emCompany = $em->getRepository(CompaniesVPN::class)
            ->findOneBy([
                'name' => $name
            ]);
        return $emCompany->getId();
    }
}
