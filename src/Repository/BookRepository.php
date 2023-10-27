<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchByRef($ref)
    {
        return $this->createQueryBuilder('b')
        ->where('b.ref=:ref')
        ->setParameter('ref',$ref)
        ->getQuery()
        ->getResult();
    }
    //sans join(), car déja en jointure
    public function show_book_by_author()
    {
        return $this->createQueryBuilder('b')
        ->orderBy('b.author','Asc')
        ->getQuery()
        ->getResult();
    }
    //avec join() pour afficher mon implementation
    public function show_book_by_year()
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->select('b')
            ->where('b.publicationDate < :year')
            ->andWhere('a.nb_books > :count')
            ->setParameter('year', new \DateTime('2023-01-01'))
            ->setParameter('count', 35)
            ->getQuery()
            ->getResult();
    }

    public function calculatepublishedBooks()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT COUNT(b) as totalUnpublishedBooks FROM App\Entity\Book b WHERE b.published = true');

        return $query->getSingleScalarResult();
    }

    public function calculateUnpublishedBooks()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('SELECT COUNT(b) as totalUnpublishedBooks FROM App\Entity\Book b WHERE b.published = false')
            ->getSingleScalarResult();
    }

    public function sumOfBooks()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('SELECT COUNT(b) as totalBooks FROM App\Entity\Book b WHERE b.category = :category')
            ->setParameter('category', 'Science Fiction')
            ->getSingleScalarResult();
    }

    public function findBooksBetweenDates()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.published = true AND b.publicationDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', new \DateTime('2014-01-01'))
            ->setParameter('endDate', new \DateTime('2018-12-31'))
            ->getResult();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
