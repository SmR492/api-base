<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

final class PostResourceTest extends ApiTestCase
{
    private ?EntityManagerInterface $em = null;

    protected function setUp(): void
    {
        parent::setUp();

        $client = static::createClient();
        $this->em = $client->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($this->em->getRepository(Post::class)->findAll() as $post) {
            $this->em->remove($post);
        }
        $this->em->flush();
    }

    public function testGetCollectionEmpty(): void
    {
        static::createClient()->request('GET', '/api/posts');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context'     => '/api/contexts/Post',
            '@id'          => '/api/posts',
            '@type'        => 'Collection',
            'totalItems'   => 0,
        ]);
    }

    public function testPostCreate(): void
    {
        $response = static::createClient()->request('POST', '/api/posts', [
            'json'    => [
                'title'   => 'Hello World',
                'content' => 'First post content.',
            ],
            'headers' => ['Content-Type' => 'application/ld+json'],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertJsonContains([
            '@type'   => 'Post',
            'title'   => 'Hello World',
            'content' => 'First post content.',
        ]);
        self::assertNotEmpty($response->toArray()['@id']);
    }

    public function testPostCreateValidationError(): void
    {
        static::createClient()->request('POST', '/api/posts', [
            'json'    => ['title' => 'x', 'content' => ''],
            'headers' => ['Content-Type' => 'application/ld+json'],
        ]);

        self::assertResponseStatusCodeSame(422);
    }

    public function testGetItemNotFound(): void
    {
        static::createClient()->request('GET', '/api/posts/999999');

        self::assertResponseStatusCodeSame(404);
    }
}
