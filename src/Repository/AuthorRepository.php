<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function show_author_by_email()
    {
        return $this->createQueryBuilder('a')
        ->orderBy('a.email','Asc')
        ->getQuery()
        ->getResult();
    }

    public function findAuthorsByNbrBooks($min, $max)
    {
        $em = $this->getEntityManager();

        return $em->createQuery('SELECT a FROM App\Entity\Author a WHERE a.nb_books BETWEEN :min AND :max')
            ->setParameters(['min' => $min, 'max' => $max])
            ->getResult();
    }

    public function deleteFakeAuthors()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('DELETE FROM App\Entity\Author a WHERE a.nb_books = 0')
            ->execute();
    }

//    /**
//     * @return Author[] Returns an array of Author objects
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

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
