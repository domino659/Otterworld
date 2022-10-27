<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\UserFactory;
use App\Factory\PostFactory;
use App\Factory\QuestionFactory;

use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(21);
        // PostFactory::createMany(10);
        // QuestionFactory::createMany(10);
        
        PostFactory::createMany(100, function () {
            return ['user' => UserFactory::random()];
        });

        QuestionFactory::createMany(200, function () {
            return ['post' => PostFactory::random()];
        });
    }
}
 