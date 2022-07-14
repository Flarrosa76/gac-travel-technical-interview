<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AppController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    
    #[Route('dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        $productos = $this->getDoctrine()->getRepository(Products::class)->findAll();
        return $this->render('dashboard/dashboard.html.twig', [
            'productos' => $productos,
        ]);
    }

    #[Route('perfil', name: 'perfil')]
    public function perfil(): Response
    {
        $productos = $this->getDoctrine()->getRepository(Products::class)->findAll();
        return $this->render('dashboard/perfil.html.twig', [
            'productos' => $productos,
        ]);
    }


    /*#[Route('/sign-up', name: 'signup')]
    public function signup(Request $parametros)
    {
        $usuario = new Users();
        $form = $this->createForm(UsersType::class, $usuario);
        $form->handleRequest($parametros);
        
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            
            $em->flush();
            return $this->render('/success.html.twig', [
           ]);   
        }
        return $this->render('/sign-up.html.twig', [
            'form' => $form->createView()
        ]);
    }*/

    /*#[Route('/create-user', name: 'createUser')]
    public function createUser(Request $parametros)
    {
        

        return $this->render('/success.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }*/
}
