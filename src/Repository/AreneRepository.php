<?php

namespace App\Repository;

use App\Entity\Arene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Arene>
 */
class AreneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arene::class);
    }

    /**
     * @return Arene[] Returns an array of Arene objects
     */
    public function findBySearchQuery(string $query): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom LIKE :query OR a.region LIKE :query OR a.lieu LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
