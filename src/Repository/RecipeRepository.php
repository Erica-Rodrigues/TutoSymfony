<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
    * @return Recipe[] Returns an array of Recipe objects
    */
    public function findRecipeDurationLowerThan(int $duration) : array
    {
        return $this->createQueryBuilder('r')
        ->where('r.duration <= :duration')
        ->orderBy('r.duration', 'ASC')
        ->setParameter('duration', $duration)
        ->getQuery()
        ->getResult();
    }
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Get published posts thanks to Search Data value
     * 
     * @param SearchData $searchData
     * @return PaginationInterface
     */
    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $data = $this->createQueryBuilder('r')
            ->addOrderBy('r.createdAt', 'DESC');

        if(!empty($searchData->q)) {
            $data = $data
                ->andWhere('r.title LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }
        $data = $data
            ->getQuery()
            ->getResult();

        $recipes = $this->paginatorInterface->paginate($data, $searchData->page, 9);

        return $recipes;
    }
}
