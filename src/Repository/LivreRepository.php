<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends Depot
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function findBySearch($mot){
        return $this->createQueryBuilder('l')
            ->andWhere('l.auteur LIKE :val OR l.titre LIKE :val')
            ->setParameter('val', '%' . $mot . '%')
            ->orderBy('l.auteur', 'ASC')
            ->addOrderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByEmpruntes()
    {
        return $this->createQueryBuilder('l')
            ->join("App\Entity\Emprunt", "e", "WITH", "e.livre=l.id")
            ->andWhere('e.date_rendu IS NULL')
            ->orderBy('l.auteur', 'ASC')
            ->addOrderBy('l.titre')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
