<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoriesController extends AbstractController
{
    private $boton_top = 'Nueva categoria';
    private $enlace_nuevo = 'app_categories_new';
    private $enlace_index = 'app_categories_index';
    

    #[Route('/', name: 'app_categories_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'titulo' => 'Listado de categorias',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/new', name: 'app_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoriesRepository $categoriesRepository): Response
    {

        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category, array(
            'etiqueta' => 'Crear',
        ));        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->add($category);
            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formulario/formulario_base.html.twig', [
            'category' => $category,
            'form' => $form,
            'titulo' => 'Nueva categoria',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,

        ]);

    }

    #[Route('/{id}', name: 'app_categories_show', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        return $this->render('categories/show.html.twig', [
            'category' => $category,
            'titulo' => 'Categoria :  <b>'. $category->getName().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(CategoriesType::class, $category, array(
            'etiqueta' => 'Actualizar',
        ));
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->add($category);
            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formulario/formulario_base.html.twig', [
            'category' => $category,
            'form' => $form,
            'titulo' => 'Editar categoria :  <b>'. $category->getName().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/{id}', name: 'app_categories_delete', methods: ['POST'])]
    public function delete(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoriesRepository->remove($category);
        }

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
