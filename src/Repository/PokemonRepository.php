<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($filters['name'])) {
            $qb->andWhere('p.nom LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['generation'])) {
            $qb->andWhere('p.generation = :generation')
                ->setParameter('generation', $filters['generation']);
        }


        if (!empty($filters['type'])) {
            // jointure sur les deux types
            $qb->leftJoin('p.type1', 't1')
                ->leftJoin('p.type2', 't2')
                ->andWhere('t1.nom LIKE :type OR t2.nom LIKE :type')
                ->setParameter('type', '%' . $filters['type'] . '%');
        }

        if (isset($filters['is_legendary']) && $filters['is_legendary'] === true) {
            $qb->andWhere('p.isLegendary = :is_legendary')
                ->setParameter('is_legendary', true);
        }

        if (isset($filters['is_mythical']) && $filters['is_mythical'] === true) {
            $qb->andWhere('p.isMythical = :is_mythical')
                ->setParameter('is_mythical', true);
        }

        if (!empty($filters['evolution_stage'])) {
            if ($filters['evolution_stage'] === 'base') {
                $qb->andWhere('p.evolutionPrecedente IS NULL');
            } elseif ($filters['evolution_stage'] === 'evolution') {
                $qb->andWhere('p.evolutionPrecedente IS NOT NULL');
            }
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * @return Pokemon[] Returns an array of Pokemon objects corresponding to the search
     */
    public function findBySearchQuery(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :query OR p.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.numeroPokedex', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
