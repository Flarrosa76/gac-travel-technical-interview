<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/users')]
class UsersController extends AbstractController
{
    private $boton_top = 'Nuevo Usuario';
    private $enlace_nuevo = 'app_users_new';
    private $enlace_index = 'app_users_index';

    #[Route('/', name: 'app_users_index', methods: ['GET'])]
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
            'titulo' => 'Listado de usuarios',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, array(
            'etiqueta' => 'Crear',
        ));        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->renderForm('formulario/formulario_base.html.twig', [
            'user' => $user,
            'form' => $form,
            'titulo' => 'Crear un esuario',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
            'titulo' => 'Usuario :  <b>'. $user->getUserIdentifier().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,               
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, UsersRepository $usersRepository, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UsersType::class, $user, array(
            'etiqueta' => 'Actualizar',
        ));        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('plainPassword')->getData()){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                );
            } 
            
            
            $usersRepository->add($user);
            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formulario/formulario_base.html.twig', [
            'user' => $user,
            'form' => $form,
            'titulo' => 'Usuario :  <b>'. $user->getUserIdentifier().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,   
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user);
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
