<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Commentaire;
use App\Form\AnnonceType;
use App\Form\CommentaireType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Annonce::class);

        $annonces = $repo->findAll();

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('index/home.html.twig');
    }



    /**
     * @Route("/annonce/new", name="annonce_create")
     */
    public function form(Request $request, ObjectManager $manager){


        $annonce = new Annonce();


        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $annonce->setCreateAt(new \DateTime())
                    ->setUser($this->getUser());

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }


        return $this->render('index/create.html.twig', [
            'formAnnonce' => $form->createView(),

        ]);

    }


    /**
     * @Route("/annonce/{id}", name="annonce_show")
     */
    public function show(Annonce $annonce, Request $request, ObjectManager $manager){

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentaire->setCreatedAt(new \DateTime())
                        ->setAnnonce($annonce)
                        ->setUser($this->getUser());

            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('annonce_show', ['id' =>$annonce->getId()]);
        }

        return $this->render('index/show.html.twig', [
            'annonce' => $annonce,
            'commentaireForm' => $form->createView()]);
    }



}

