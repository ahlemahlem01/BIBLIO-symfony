<?php

namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\form\AlayaType;

class BookController extends AbstractController
{


    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('books/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


    #[Route('/showbook', name: 'showbook')]
    public function showbook(BooksRepository $repo): Response
    {
        $books=$repo->findAll() ;
        return $this->render('books/showbook.html.twig', [
            'book' => $books,
        ]);
    }



    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $managerRegistry ,Request $request): Response
    {

        $em=$managerRegistry->getManager() ;

        $books =new Books();
        //    $books->setPublished(true) ;

        $form=$this->createForm(AlayaType::class , $books)   ;     //bch nasn3ou form fiha deux parametreS li hiya lform w l'instance mteena

        $form->handleRequest($request) ;               //les donnes de requete http sont associé au formulaire

        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les champs hatt'hom valid wela

            $em->persist($books);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute
            return $this->redirectToRoute('showbook') ;   //lehna nhott esm route mch rouute (name)

        }
        return $this->renderForm('books/addbook.html.twig', [
            'f' => $form
        ]);
    }





    #[Route('/editbook/{ref}', name: 'editbook')]
    public function editbook( $ref,ManagerRegistry $manager, BooksRepository $bookrepo, Request $request): Response
    {
        $em=$manager->getManager();
        $dataref=$bookrepo->find($ref);
        // var_dump($dataid).die() ;
        $form=$this->createForm(AlayaType::class,$dataref) ;
        $form->handleRequest($request) ;

        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les camps hatt'hom valid wela

            $em->persist($dataref);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute
            return $this->redirectToRoute('showbook') ;

        }

        return $this->renderForm('books/editbook.html.twig', [
            'x' => $form,
        ]);
    }


    #[Route('/deletebook/{ref}', name: 'deletebook')]
    public function deleteauthor($ref, ManagerRegistry $manager, BooksRepository $repo): Response
    {
        $emm = $manager->getManager();
        $ref_remove = $repo->find($ref);
        $emm->remove($ref_remove);
        $emm->flush();


        return $this->redirectToRoute('showbook');
    }

}
