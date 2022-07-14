<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;

class UsersFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = HttpClient::create();       
        $response = $client->request('GET', 'https://fakestoreapi.com/users');
        $usuarios = $response->toArray();
        foreach($usuarios as $usuario){
            $newus = new Users();
            $newus->setUsername($usuario['username']);
            $newus->setPassword($usuario['password']);
            $newus->setActive(true);
            $manager->persist($newus);          
        }
        $newus = new Users();
        $newus->setUsername('admin');
        $newus->setPassword('$2y$13$bYXjz32LjDWMm28Ti.kXPe8JsCZDESPpA4CUILWJfB/P2VyzZ9WyS'); // passwword 12341234
        $newus->setRoles(['ROLE_ADMIN']);
        $newus->setActive(true);
        $manager->persist($newus);

        $newus = new Users();
        $newus->setUsername('user');
        $newus->setPassword('$2y$13$bYXjz32LjDWMm28Ti.kXPe8JsCZDESPpA4CUILWJfB/P2VyzZ9WyS'); // passwword 12341234
        $newus->setRoles(['ROLE_USER']);
        $newus->setActive(true);
        $manager->persist($newus);

        $manager->flush();
    }
}
