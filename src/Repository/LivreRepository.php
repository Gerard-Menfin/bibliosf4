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

    /**
     * Requête avec jointure
     * @return Array of App\Entity\Livre object
     */
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

    /**
     * Nombre de livres empruntés actuellement
     * SELECT COUNT(*)
     * FROM livre l
     *  JOIN emprunt e ON l.id = e.livre_id
     * WHERE e.date_rendu IS NULL
     * @return integer
     */
    public function nbSortis() : int
    {
        $requete = $this->createQueryBuilder("l")
                        ->select("COUNT(l.id) as nb")
                        ->join("App\Entity\Emprunt", "e", "WITH", "e.livre=l.id")
                        ->andWhere('e.date_rendu IS NULL')
                        ->getQuery()
                        ->getOneOrNullResult();
        return $requete ? (int)$requete["nb"] : 0;
    }

    public function nbDisponibles()
    {
        return $this->nb() - $this->nbSortis();
    }
}
