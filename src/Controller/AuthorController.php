<?php

namespace App\Controller;

use App\Entity\Author;
use App\form\AhlemType;
use App\form\MinmaxType;
#use App\form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\form\SubmitType;

class AuthorController extends AbstractController
{

  //  public  $authors = array(
      //  array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
    //    array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
      //   array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
      //  );

     #[Route('/author', name: 'app_author')]
    public function index(): Response

    {         return $this->render('author/index.html.twig', [
          'controller_name' => 'AuthorController',
         ]);
  }



//     #[Route('/showtableauthor', name: 'app_showtableauthor')]
//     public function showtableauthor(): Response
//     {
//         return $this->render('author/showtableauthor.html.twig', [
//             'author' => $this->authors
//         ]);
//     }



//     #[Route('/auhtorDetails/{id}', name: 'author_details')]
//     public function auhtorDetails($id): Response
//     {
//       //  var_dump($id).die();
//         $x =null;
//         foreach($this->authors as $authordddddd)
//         {
//             if ($authordddddd['id']== $id){
//                 $x=$authordddddd ;
//             }
//         }
//         //var_dump($x).die() ;
//         return $this->render('author/showdb.html.twig', [
//             'authorr' => $x

//         ]);
//     }

    //read
    #[Route('/showdb', name: 'showdb')]
    public function showdb(AuthorRepository $repo ,Request $request): Response
    {
        $authors=$repo->findAll() ;
        /****************************************************** */
        // $Form=$this->createForm(SearchType::class )   ;
        /**************************************************** */
        $form=$this->createForm(MinmaxType::class )   ;
        $form->handleRequest($request) ;
        if($form->isSubmitted())
        {
            /********************************************************** */
            // $datainput=$form->get('username')->getData();
            /********************************************************** */
            $min=$form->get('min')->getData();
            $max=$form->get('max')->getData();
            /************************************************************ */
            //  $authors=$repo->searchusername($datainput) ;

            $authors=$repo->minmax($min , $max ) ;
            return $this->render('author/showdb.html.twig', [
                'author' => $authors,
                'f' => $form,
            ]);
        }
        //  $authors=$repo->orderbyusername() ;   //query order by asc (majuscule) : just for testing

        //  $authors=$repo->searchbyalphabet() ;
        return $this->render('author/showdb.html.twig', [
            'author' => $authors,
            'f' => $form,
        ]);
    }



//  //ajout static
//  #[Route('/addauthor', name: 'addauthor')]
//  public function addauthor(ManagerRegistry $manager,Request $request): Response    //tetsama persistance
//  {
//      $em=$manager->getManager() ;     //donner l'acces au fonctionalite (persiste/flush)

//      $authors =new Author();
//      $authors->setUsername("3a58");
//      $authors->setEmail("3a58@esprit.tn");

//      $em->persist($authors);   //t'hadher requet INSERT
//      $em->flush() ;             //execute

//      return new Response("great add") ;
//  }


    //ajout
    #[Route('/addformauthor', name: 'addformauthor')]
    public function addformauthor(ManagerRegistry $managerRegistry ,Request $request): Response
    {
        $em=$managerRegistry->getManager() ;             //donner l'acces au fonctionalite (persiste/flush)

        $authors =new Author();
        $form=$this->createForm(AhlemType::class , $authors)   ;     //bch nasn3ou form fiha deux parametre li hiya lform w l'instance mteena
        $form->handleRequest($request) ;               //les donnes de requete http sont associé au formulaire

        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les champs hatt'hom valid wela

            $em->persist($authors);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute
            return $this->redirectToRoute('showdb') ;   //lehna nhott esm route mch rouute (name)

        }
        return $this->render('author/addformauthor.html.twig', [
            'f' => $form->createView(),
        ]);
    }



    #[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor( $id,ManagerRegistry $manager, AuthorRepository $authorRepo, Request $request): Response
    {
        $em=$manager->getManager();
        $dataid=$authorRepo->find($id);
        // var_dump($dataid).die() ;
        $form=$this->createForm(AhlemType::class,$dataid) ;
        $form->handleRequest($request) ;

        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les camps hatt'hom valid wela

            $em->persist($dataid);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute
            return $this->redirectToRoute('showdb') ;

        }

        return $this->renderForm('author/editauthor.html.twig', [
            'x' => $form,
        ]);
    }

    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id, ManagerRegistry $manager, AuthorRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();


        return $this->redirectToRoute('showdb');
    }

   #[Route('/showbook/{id}', name: 'showbbook')]
   public function showbook($id,AuthorRepository $repo): Response
    {
        $book =$repo->searchbyid($id) ;
        return $this->render('author/showdb.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }



}


