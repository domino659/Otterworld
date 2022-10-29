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
        UserFactory::createOne([
            'username' => 'admin',
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'username' => 'user',
        ]);
        UserFactory::createMany(21);
        
        PostFactory::createMany(100, function () {
            return ['user' => UserFactory::random()];
        });

        QuestionFactory::createMany(200, function () {
            return ['user' => UserFactory::random(),
                    'post' => PostFactory::random()];
        });
    }
}
 