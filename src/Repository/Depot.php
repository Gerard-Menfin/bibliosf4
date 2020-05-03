<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/*
    Toutes les classes Repository vont hériter de cette classe Depot
    pour avoir des méthodes génériques
*/
abstract class Depot extends ServiceEntityRepository
{
    /**
     * Renvoie le nombre d'enregistrement de la table
     * @return int
     */
    public function nb() : int
    {
        $resultat = $this->createQueryBuilder('a')
                        ->select("COUNT(a.id) as nb")
                        ->getQuery()
                        ->getOneOrNullResult()
                    ;
        return $resultat ? $resultat["nb"] : 0;
    }
}
