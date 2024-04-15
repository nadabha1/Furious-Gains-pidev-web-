<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Categorie;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id_produit, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
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

//  
public function add(Produit $entity, bool $flush = false): void
{
    $this->getEntityManager()->persist($entity);

    if ($flush) {
        $this->getEntityManager()->flush();
    }
}
public function listProduitByMarque(): array
{
    return $this->createQueryBuilder('a')
        ->orderBy('a.marque_produit', 'ASC')
        ->getQuery()
        ->getResult();
}
public function findAllCategories(): array
{
    $queryBuilder = $this->createQueryBuilder('p')
        ->select('c.id_categorie')
        ->distinct(true)
        ->leftJoin('p.id_categorie', 'c'); // Utilisez une jointure avec la relation categorie de l'entité Produit

    $categories = $queryBuilder->getQuery()->getResult();

    return $categories;
}





}