<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
    * @return Post[] 
    * Returns Post objects for the 10 most recents posts 
    *
    */
    public function findLast10()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Post[] 
    * Returns Post objects for the 10 most like pictures that are last than 7 days OLd 
    *
    */
    public function topLove()
    {
/*        $teamsingroup = $em->getRepository("AppBundle\Model\Entity\Team")
                    ->createQueryBuilder('o')
                    ->innerJoin('o.userLIke', 't')
*/
        $l7day = date('Y-m-d h:i:s', strtotime("-7 days"));
        // server date seem to be US based so we need to add & day
        $today = date('Y-m-d h:i:s', strtotime("+1 days")); 

        //dd(date('Y-m-d h:i:s'));

        return $this->createQueryBuilder('p')
            ->select('p','COUNT(p.id) as countLike')
            ->innerJoin('p.userLike', 'u')
            ->where('p.createdAt >=:l7day and p.createdAt<= :today' )
            ->setParameter('today', $today)
            ->setParameter('l7day', $l7day)
            ->groupBy('p.id')
            ->orderBy('countLike', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
