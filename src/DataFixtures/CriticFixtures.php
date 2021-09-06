<?php

namespace App\DataFixtures;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Critic;
use App\Entity\Commentary;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;

class CriticFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $users = [];
        $albums = [
        '159ORixBSSemxiualv1Woj',
        '7CkhUIDNj8IIc3UMlr7Sua',
        '6o7OzvIJJMkhgB1QtEB1fj',
        '5P0LN9xrnLY1By7mlkKtkx',
        '6JGxui5S9EyVrlnzoxb53W',
        '2nGL73TqdduRKepdcwJvdm',
        '5ZMR23VEAYRBc1YfD8FYmO',
        '1iMaUf2YO6TII4NSiHyiAm',
        '3fOMcUAfEDO0Dnx8Q9Y1SF',
        '6ZaxAEhaMsZboaMi9tAF3P',
        '5FCJ8HyYcx3OwI0UE4CvK0',
        '0Sz6IKhmINnfWG77X5ScoH',
        '3jQX0XtNGeIQ06GX4Ren9l',
        '5IYhB6cNGiCogm6tcMRhBW'
    ];

        for ($i = 0 ; $i<20; $i++){
            $user = new User();
            $user->setUsername(uniqid($faker->firstName))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setPassword('sadfasdfasdfasdfasdfasfdasdf')
                ->setEmail(uniqid($faker->email))
                ->setBirthday(new \DateTime('now'));
                
            $manager->persist($user);
            $users[] = $user;
        }

        for($j = 0 ; $j < 20; $j++){

            $critic = new Critic();
        
            $critic->setContent($faker->realText(200, 2))
            ->setCreatedAt(new \DateTime())
            ->setUser($users[random_int(0, count($users) - 1)])
            ->setAlbumId($albums[random_int(0, count($albums) - 1)])
            ->setRating(random_int(1,10));

            $manager->persist($critic);

            foreach($users as $user ){
                if (rand(0,5) >= 2){
                    $like = new Like();
                    $like->setCritic($critic)
                        ->setUser($user);
                    if (rand(0,5) >= 2){
                       $like->setValue(true);
                    } else {
                        $like->setValue(false);
                    } 
                    $manager->persist($like);
                }
                for($k = 0; $k < 3; $k++){
                    $comment = new Commentary();
                    $comment->setContent('asfdasdffa')
                        ->setUser($user)
                        ->setCreatedAt(new \Datetime('now'))
                        ->setCritic($critic);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
