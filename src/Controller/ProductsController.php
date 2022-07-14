<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Form\ProductsType;
use App\Form\StockType;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{

    private $boton_top = 'Nuevo Producto';
    private $enlace_nuevo = 'app_products_new';
    private $enlace_index = 'app_products_index';

    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        $repoCat= $this->getDoctrine()->getRepository(Categories::class);
        $cats = $repoCat->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
            'titulo' => 'Listado de Productos',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsRepository $productsRepository): Response
    {

        
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product, array(
            'etiqueta' => 'Crear',
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->add($product);
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formulario/formulario_base.html.twig', [
            'product' => $product,
            'form' => $form,
            'titulo' => 'Nuevo Producto',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {

        return $this->render('products/show.html.twig', [
            'product' => $product,
            'titulo' => 'Producto :  <b>'. $product->getName().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        $form = $this->createForm(ProductsType::class, $product, array(
            'etiqueta' => 'Actualizar',
        ));  
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->add($product);
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formulario/formulario_base.html.twig', [
            'product' => $product,
            'form' => $form,
            'titulo' => 'Producto :  <b>'. $product->getName().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,
        ]);
    }


    
    #[Route('/{id}/stock', name: 'app_products_stock', methods: ['GET', 'POST'])]
    public function stock(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        $form = $this->createForm(StockType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cantidad = $request->request->get('stock')['cantidad'];
            $stockModificado = $product->getStock()-$cantidad;
            if ($stockModificado>=0){
                $product->setStock($stockModificado);
                $productsRepository->add($product);
                return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash(
                    'error',
                    '¡La cantidad ingresada es mayor a la disponible!'
                );
            }
        }

        return $this->renderForm('products/stock.html.twig', [
            'product' => $product,
            'form' => $form,
            'titulo' => 'Cambios de stock',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,            
        ]);
    }

    #[Route('/historic/{id}', name: 'app_products_historic', methods: ['GET'])]
    public function historic(Products $product): Response
    {
        return $this->render('products/historic.html.twig', [

            'producto' => $product,
            'historico' => $product->getStockHistorics(),
            'titulo' => 'Historico de movimientos de: <b>'. $product->getName().'</b>',
            'boton' => $this->boton_top,
            'enlace_index' => $this->enlace_index,
            'enlace_nuevo' => $this->enlace_nuevo,                  
        ]);
    }

}

