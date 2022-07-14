<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;

class CategoriesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = HttpClient::create();       
        $response = $client->request('GET', 'https://fakestoreapi.com/products/categories');
        $categorias = $response->toArray();
        foreach($categorias as $categoria){
            $newcat = new Categories();
            $newcat->setName($categoria);
            $manager->persist($newcat);          
        }
        $manager->flush();
    }
}
