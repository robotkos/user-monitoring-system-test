<?php

namespace App\Controller;

use App\Entity\CompaniesVPN;
use App\Entity\TransferLogsVPS;
use App\Entity\UsersVPS;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use function PHPSTORM_META\elementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

class DefaultController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function index()
    {
        $template = 'default/index.html.twig';
        $userData = $this->getUser();
        return $this->render($template, array(
            'url' => $this->generateUrl('index'),
            'user' => $userData->getUsername()
        ));
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function users()
    {
        $template = 'default/users.html.twig';
        $userData = $this->getUser();
        return $this->render($template, array(
            'url' => $this->generateUrl('users'),
            'user' => $userData->getUsername()
        ));
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function companies()
    {
        $template = 'default/companies.html.twig';
        $userData = $this->getUser();
        return $this->render($template, array(
            'url' => $this->generateUrl('companies'),
            'user' => $userData->getUsername()
        ));
    }

    /**
     * @Rest\Route("/logout", name="logout")
     */
    public function logout(){

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
     * @SWG\Tag(name="Data Workers")
     * @Security(name="Bearer")
     */

    public function generateData()
    {
        $em = $this->em;
        $faker = new Factory();
        $vpsRepository = $em->getRepository(UsersVPS::class);
        $vpsRepository->clearTable($em);
        $userIDs = $vpsRepository->findOneColumn()->getQuery()->getResult();
        $last6Months = $this->getLast6Months();
        foreach ($last6Months as $months) {
            foreach ($userIDs as $userID) {
                for ($i = 0; $i <= rand(9, 80); $i++) {
                    $randomSize = $this->FileSizeConvert(rand(100, 1099511627776));
                    $transferLogs = new TransferLogsVPS();
                    $transferLogs->setUserId($userID['id']);
                    $transferLogs->setCompanyId($userID['companyId']);
                    $transferLogs->setResource($faker->create()->domainName);
                    $transferLogs->setTransfered($randomSize['bytes']);
                    $transferLogs->setDateTime(new \DateTime($months['time'][array_rand($months['time'])]));
                    $em->persist($transferLogs);
                };
            }
        }
        $em->flush();
        return new JsonResponse(['message' => 'Generated'], 200);
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
     *     name="months",
     *     in="query",
     *     type="string",
     *     description="The Mounth for Report"
     * )
     * @SWG\Tag(name="Data Workers")
     * @Security(name="Bearer")
     */

    public function reportList(Request $request)
    {
        $em = $this->em;
        $months = $request->query->get('months');
        $transfersByMonths = $this->getTransfersByMonths($months, $em);
        $checkQuota = $this->compareQuota($transfersByMonths, $em);

        $serializer = $this->get('serializer');
        $json = $serializer->serialize($checkQuota, 'json');
        return new JsonResponse($json, 200);
    }

    private function compareQuota($transfersByMonths, EntityManagerInterface $em): array
    {
        $emCompanies = $em->getRepository(CompaniesVPN::class);
        $result = [];
        foreach ($transfersByMonths as $key => $companyTransfered) {
            $companyQuota = $emCompanies->findOneBy(
                [
                    'id' => $key
                ]
            );
            $cQuota = $companyQuota->getQuota() * 1099511627776;
            if ($cQuota < $companyTransfered) {
                $result[] = [
                    'company' => $companyQuota->getName(),
                    'used' => $companyTransfered,
                    'quota' => $cQuota,
                ];
            }
        }
        return $result;
    }

    private function getTransfersByMonths($months, EntityManagerInterface $em)
    {
        $monthss = $this->getLast6Months()[0]['month'];

        $result = $em->getRepository(TransferLogsVPS::class)->getByDate($monthss);

        return $this->getCompaniesTranferData($result);
    }

    private function getCompaniesTranferData(QueryBuilder $qb): array
    {
        $result = $qb->getQuery()->getResult();
        $final = [];
        foreach ($result as $tranferedEntity) {
            if (isset($final[$tranferedEntity->getCompanyId()])) {
                $final[$tranferedEntity->getCompanyId()] += $tranferedEntity->getTransfered();
            } else {
                $final[$tranferedEntity->getCompanyId()] = $tranferedEntity->getTransfered();
            }
        }
        return $final;
    }

    private function getLast6Months(): array
    {
        $months = [];
        for ($i = 1; $i < 6; $i++) {
            for ($n = 1; $n < 28; $n++) {
                $dateTime = date($n . ' M Y ' . rand(10, 23) . ':' . rand(10, 59) . ':' . rand(10, 59), strtotime("-$i month"));
                $time[] = date('d M Y H:i:s', strtotime($dateTime));
            }
            $months[] = [
                'month' => date('F Y', strtotime("-$i month")),
                'time' => $time

            ];
        }
        return $months;
    }

    private function FileSizeConvert($bytes): array
    {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return [
            'bytes' => $bytes,
            'view' => $result
        ];
    }
}
