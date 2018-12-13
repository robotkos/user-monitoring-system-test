<?php

namespace App\Controller;

use App\Entity\TransferLogsVPS;
use App\Entity\UsersVPS;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
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
        $userIDs = $em->getRepository(UsersVPS::class)->findOneColumn()->getQuery()->getResult();
        $last6Mouth = $this->getLast6Mouth();
        foreach ($last6Mouth as $month) {
            foreach ($userIDs as $userID) {
                for ($i = 0; $i <= rand(9, 80); $i++) {
                    $randomSize = $this->FileSizeConvert(rand(100, 10995116277760));
                    $transferLogs = new TransferLogsVPS();
                    $transferLogs->setUserId($userID['id']);
                    $transferLogs->setResource($faker->create()->address);
                    $transferLogs->setTransfered($randomSize['bytes']);
                    $transferLogs->setDateTime(new \DateTime($month['time'][array_rand($month['time'])]));
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
     *     name="mounth",
     *     in="query",
     *     type="string",
     *     description="The Mounth for Report"
     * )
     * @SWG\Tag(name="Data Workers")
     * @Security(name="Bearer")
     */

    public function reportList(Request $request)
    {

        return new JsonResponse(['data' => 'yes'], 200);
    }

    private function getLast6Mouth(): array
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
