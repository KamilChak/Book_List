<?php

namespace App\Controller;

use App\Form\FilterMinMaxType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/show_authors', name: 'show_authors')]
    public function show_authors(AuthorRepository $authorRepository,Request $req): Response
    {
        $form=$this->createForm(FilterMinMaxType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $min = $form->get('min')->getData();
            $max = $form->get('max')->getData();
            $authors = $authorRepository->findAuthorsByNbrBooks($min,$max);
            return $this->renderForm('author/show_authors.html.twig', [
                'authors' => $authors,
                'f' => $form,
            ]);
        }
        return $this->renderForm('author/show_authors.html.twig', [
            'authors'=>$authorRepository->show_author_by_email(),
            'f' => $form,
        ]);
    }

    #[Route('/deleteFakeauthors', name: 'deleteFakeauthors')]
    public function deleteFakeauthors(AuthorRepository $authorRepository): Response
    {
        $authorRepository->deleteFakeAuthors();
        return $this->redirectToRoute('show_authors');
    }
}
