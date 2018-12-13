<?php

namespace App\Repository;

use App\Entity\TransferLogsVPS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TransferLogsVPS|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransferLogsVPS|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransferLogsVPS[]    findAll()
 * @method TransferLogsVPS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransferLogsVPSRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransferLogsVPS::class);
    }

    public function getByDate(string $monthss)
    {
        $datetimeStart = new \DateTime('01 ' . $monthss);
        $datetimeEnd = new \DateTime('31 ' . $monthss);
        $from = new \DateTime($datetimeStart->format("M d")." 00:00:00");
        $to   = new \DateTime($datetimeEnd->format("M d")." 00:00:00");

        $qb = $this->createQueryBuilder("e");
        $qb
            ->andWhere('e.date_time BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
        ;

        return $qb;
    }

    // /**
    //  * @return TransferLogsVPS[] Returns an array of TransferLogsVPS objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransferLogsVPS
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
