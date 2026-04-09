<?php

namespace App\Repository;

use App\Entity\Part;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Part>
 */
class PartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Part::class);
    }

    /**
     * Retourne les pièces disponibles en stock, avec marque et catégorie chargées.
     *
     * @return Part[]
     */
    public function findAvailableParts(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->join('p.category', 'c')
            ->addSelect('b', 'c')
            ->where('p.isAvailable = :available')
            ->andWhere('p.stock > 0')
            ->setParameter('available', true)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des pièces par marque.
     *
     * @return Part[]
     */
    public function findByBrand(int $brandId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->join('p.category', 'c')
            ->addSelect('b', 'c')
            ->where('b.id = :brandId')
            ->setParameter('brandId', $brandId)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des pièces par catégorie.
     *
     * @return Part[]
     */
    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->join('p.category', 'c')
            ->addSelect('b', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche full-text simple sur le nom ou la référence.
     *
     * @return Part[]
     */
    public function search(string $term): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->join('p.category', 'c')
            ->addSelect('b', 'c')
            ->where('p.name LIKE :term OR p.reference LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
