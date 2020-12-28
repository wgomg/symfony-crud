<?php

namespace App\Repository;

use App\Entity\Nivel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nivel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nivel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nivel[]    findAll()
 * @method Nivel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nivel::class);
    }

    // /**
    //  * @return Nivel[] Returns an array of Nivel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nivel
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
