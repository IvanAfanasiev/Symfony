<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getLast(): ?Article{
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
        ->orderBy('a.dateAdded', 'desc')
        ->setMaxResults(1);
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
    public function findOne(?string $value=""):?array{
        
        $queryBuilder = $this->createQueryBuilder('a');
        
        $queryBuilder->where('LOWER(a.title) LIKE LOWER(:val)');
        $queryBuilder->orwhere('LOWER(a.content) LIKE LOWER(:val)');
        $queryBuilder->setParameter('val', '%' . $value . '%');
        $queryBuilder->orderBy('a.dateAdded', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
    //    /**
    //     * @return Article[] Returns an array of Article objects
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

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
