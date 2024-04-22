<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($cin, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    public function findBycin($value): array
   {
       return $this->createQueryBuilder('a')
           ->andWhere('cin = :val')
           ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
           ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function listUserByEmail(): ?User
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.email','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findOneByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }
    public function searchNom($Email)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom LIKE :ncl')
            ->setParameter('ncl', $Email.'%')
            ->getQuery()
            ->execute();
    }
}