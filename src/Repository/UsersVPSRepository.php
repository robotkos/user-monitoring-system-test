<?php

namespace App\Repository;

use App\Entity\UsersVPS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UsersVPS|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersVPS|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersVPS[]    findAll()
 * @method UsersVPS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersVPSRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UsersVPS::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */

    public function findOneColumn()
    {
        return $this->createQueryBuilder('u')
            ->select('u.id')
            ;
    }
    // /**
    //  * @return UsersVPS[] Returns an array of UsersVPS objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersVPS
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
