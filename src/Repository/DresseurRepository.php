<?php

namespace App\Repository;

use App\Entity\Dresseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dresseur>
 */
class DresseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dresseur::class);
    }

    /**
     * @return Dresseur[] Returns an array of Dresseur objects
     */
    public function findBySearchQuery(string $query): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.nom LIKE :query OR d.prenom LIKE :query OR d.villeNatale LIKE :query OR d.region LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('d.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
