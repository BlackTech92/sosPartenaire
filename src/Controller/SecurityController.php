<?php

namespace App\Controller;

use App\Form\InscriptionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_inscription")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getMdp());

            $user->setMdp($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_connexion');
        }

        return $this->render('security/inscription.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_connexion")
     */
    public function login(){
        return $this->render('security/connexion.html.twig');
    }


    /**
     * @Route("/deconnexion", name="security_deconnexion")
     */
    public function logout(){

    }
}
