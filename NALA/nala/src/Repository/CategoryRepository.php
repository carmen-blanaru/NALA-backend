<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // this function will recover the list of the categories and will show 5 pictures per category
    public function listCategoryLimitedFivePictures()
    {  
        // the variable $categories stores the result of the request ==> the list of the categories
        $categories = $this->createQueryBuilder('cat')
        ->getQuery()
        ->execute();
       
        // in order to  show the pictures for each category, the loop foreach is necessary
        foreach ($categories as $currentCategory) 
        {
            $currentCategoryName = $currentCategory->getName();
            $result[] = $this->createQueryBuilder('c')
            ->where('c.name = :categoryName')->setParameter('categoryName', $currentCategoryName)
            ->leftJoin('c.posts', 'p')
            ->setMaxResults(5)
            ->addSelect('p')
            ->getQuery()
            ->execute();
        }
        return $result ;
    }
    
    

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
