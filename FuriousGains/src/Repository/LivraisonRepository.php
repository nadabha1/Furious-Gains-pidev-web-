<?php

namespace App\Repository;


use App\Entity\Livraison;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livraison>
 *
 * @method Livraison|null find($id_livraison, $lockMode = null, $lockVersion = null)
 * @method Livraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livraison[]    findAll()
 * @method Livraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livraison::class);
    }

//    /**
//     * @return Livraison[] Returns an array of Livraison objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livraison
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function listLivraisonByEmail(): ?Livraison
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.adresseLivraison', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByKeywordQuery(string $keyword)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.statutLivraison LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->getQuery()
            ->getResult();
    }
    public function findOneByadresse(string $keyword)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.adresseLivraison LIKE :keyword')
            ->setParameter('keyword','%'.$keyword.'%')
            ->getQuery()
            ->getResult();
    }
}