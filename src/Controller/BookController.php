<?php

namespace App\Controller;

use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/show_books', name: 'show_books')]
    public function show_books(BookRepository $bookRepository,Request $req): Response
    {
        $form=$this->createForm(SearchType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $data = $form->get('ref')->getData();
            $books = $bookRepository->searchByRef($data);
            return $this->renderForm('book/show_books.html.twig', [
                'books' => $books,
                'f' => $form,
            ]);
        }
        $publishedBooks = $bookRepository->calculatepublishedBooks();
        $unpublishedBooks = $bookRepository->calculateUnpublishedBooks();
        $totalBooks = $bookRepository->sumOfBooks();
        $booksb = $bookRepository->findBooksBetweenDates();
        return $this->renderForm('book/show_books.html.twig', [
            'books' => $bookRepository->show_book_by_author(),
            //'books' => $bookRepository->show_book_by_year(),
            'f' => $form,
            'pb' => $publishedBooks,
            'upb' => $unpublishedBooks,
            'total' => $totalBooks,
            'booksb' => $booksb,
        ]);
    }

    #[Route('/edit_book/{ref}', name: 'edit_book')]
    public function edit_book($ref,BookRepository $bookRepository,ManagerRegistry $mr,Request $req): Response
    {
        $em = $mr->getManager();
        $book = $bookRepository->find($ref);
        $form=$this->createForm(BookType::class, $book);    
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('show_books');
        }
        
        return $this->renderForm('book/edit_book.html.twig', [
            'f'=>$form,
        ]);
    }

}
