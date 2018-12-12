<?php

namespace App\Repository;

use App\Entity\CompaniesVPN;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompaniesVPN|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompaniesVPN|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompaniesVPN[]    findAll()
 * @method CompaniesVPN[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompaniesVPNRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompaniesVPN::class);
    }

    // /**
    //  * @return CompaniesVPN[] Returns an array of CompaniesVPN objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompaniesVPN
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
