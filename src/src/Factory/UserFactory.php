<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ModelFactory<User>
 *
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[] createSequence(array|callable $sequence)
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create(array|callable $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        parent::__construct();

        $this->hasher = $hasher;
    }

    protected function getDefaults(): array
    {
        return [
            'username' => self::faker()->userName(),
            'isAdmin' => self::faker()->boolean(),
            'votes' => self::faker()->numberBetween(-20, 50),
            'createdAt' => self::faker()->dateTimeBetween('-100 days', '-1 second'),
            'updatedAt' => self::faker()->dateTimeBetween('-50 days', '-1 second')
        ];
    }

    protected function initialize(): self
    {
        return $this->afterInstantiate(function(User $user) {
            $email = strtolower($user->getUsername()) . "@gmail.com";
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
        });
    }

    protected static function getClass(): string
    {
        return User::class;
    }

    // /**
    //  * @param Request $request
    //  * @param EntityManagerInterface $em
    //  * @return Response
    //  * @Route("/user/sign-in", name="app_user_new")
    //  */

    // public function new(Request $request,
    //                     EntityManagerInterface $em,
    //                     UserPasswordHasherInterface $hasher,
    //                     UserAuthentificatorInterface $authentificator,
    //                     LoginFormAuthentificator $loginFormAuthentificator): Response
    // {
    //     $user = new User();
    //     $user->setUsername($request->request->get('username'))
    //     ->setEmail($request->request->get('email'))
    //     ->setPassword($hasher->hashPassword($user, $request->request->get('password')));

    //     $em->persist($user);
    //     $em->flush();

    //     return $authentificator->authenticateUser($user, $loginFormAuthetificator, $request);
    // }
}
