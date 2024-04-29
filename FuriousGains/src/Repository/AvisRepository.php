<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 *
 * @method Avis|null find($cin, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

//    /**
//     * @return Avis[] Returns an array of Avis objects
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

//    public function findOneBySomeField($value): ?Avis
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function listAvisByEmail(): ?Avis
    {
        return $this->createQueryBuilder('a')
            ->orderBy('note','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findOneByNote(string $email)
    {
        return $this->findOneBy(['note' => $email]);
    }
    public function findByKeywordQuery(string $keyword)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.note LIKE :keyword')
            ->setParameter('keyword','%'.$keyword.'%')
            ->getQuery()
            ->getResult();
    }
    public function findByuser(string $keyword)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.produit LIKE :keyword')
            ->setParameter('keyword','%'.$keyword.'%')
            ->getQuery()
            ->getResult();
    }
}