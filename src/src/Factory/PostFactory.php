<?php

namespace App\Factory;

use App\Entity\Post;
use App\Repository\PostRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @extends ModelFactory<Post>
 *
 * @method static Post|Proxy createOne(array $attributes = [])
 * @method static Post[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Post[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Post|Proxy find(object|array|mixed $criteria)
 * @method static Post|Proxy findOrCreate(array $attributes)
 * @method static Post|Proxy first(string $sortedField = 'id')
 * @method static Post|Proxy last(string $sortedField = 'id')
 * @method static Post|Proxy random(array $attributes = [])
 * @method static Post|Proxy randomOrCreate(array $attributes = [])
 * @method static Post[]|Proxy[] all()
 * @method static Post[]|Proxy[] findBy(array $attributes)
 * @method static Post[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Post[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PostRepository|RepositoryProxy repository()
 * @method Post|Proxy create(array|callable $attributes = [])
 */
final class PostFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->userName(),
            'content' => self::faker()->text(),
            'price' => self::faker()->numberBetween(50, 500),
            'image_filename' => "otter_profile.jpg",
            'createdAt' => self::faker()->dateTimeBetween('-100 days', '-1 second'),
            'updatedAt' => self::faker()->dateTimeBetween('-50 days', '-1 second')
        ];
    }

    protected function initialize(): self
    {
        return $this->afterInstantiate(function(Post $post) {
            $slugger = new AsciiSlugger();
            $post->setSlug($slugger->slug($post->getTitle()));
        });
        // return $this;
    }

    protected static function getClass(): string
    {
        return Post::class;
    }
}
