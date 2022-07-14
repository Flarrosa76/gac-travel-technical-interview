<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;

class ProductsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = HttpClient::create();

        $repoCat= $manager->getRepository(Categories::class);
        $categorias = $repoCat->findAll();
        foreach($categorias as $categoria){
            $response = $client->request('GET', 'https://fakestoreapi.com/products/category/'.$categoria->getName());
            $productos = $response->toArray();
            foreach($productos as $producto){
                $newproducto = new Products();
                $newproducto->setName($producto['title']);
                $newproducto->setCategory($categoria);
                $newproducto->setStock(rand(1,100));
                $manager->persist($newproducto);          
            } 
         }
        $manager->flush();
    }
}
