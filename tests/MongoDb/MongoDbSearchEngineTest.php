<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;
use Larium\Search\Criteria;
use PHPUnit\Framework\TestCase;
use Larium\Search\ResultPaginator;

class MongoDbSearchEngineTest extends TestCase
{
    private Collection $collection;

    private Client $client;

    private Database $db;

    public function setUp(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $this->client = $this->createClient();
        $this->db = $this->client->test;
        $this->collection = $this->db->selectCollection('fruits');
        $fruits = [
            [
                '_id' => 1,
                'name' => 'apples',
                'qty' => 5,
                'rating' => 3,
                'color' => 'red',
                'type' => ['fuji', 'honeycrisp']
            ],
            [
                '_id' => 2,
                'name' => 'bananas',
                'qty' => 7,
                'rating' => 4,
                'color' => 'yellow',
                'type' => ['cavendish']
            ],
            [
                '_id' => 3,
                'name' => 'oranges',
                'qty' => 6,
                'rating' => 2,
                'type' => ['naval', 'mandarin']
            ],
            [
                '_id' => 4,
                'name' => 'pineapples',
                'qty' => 3,
                'rating' => 5,
                'color' => 'yellow'
            ]
        ];
        $result = $this->collection->insertMany($fruits);
    }

    public function tearDown(): void
    {
        $this->db->dropCollection('fruits');
    }

    public function testSearchEngineWithOneQuery(): void
    {
        $criteria = Criteria::fromQueryParams('fruits', ['name' => 'apple']);

        $c = $this->collection;
        $b = new FilterBuilder();
        $e = new MongoDbSearchEngine($b, $c);

        $e->add(new FruitNameBuilder())
            ->add(new FruitColorBuilder())
        ;

        $result = $e->match($criteria);

        $data = $result->fetch(0, 10);

        self::assertEquals(2, count($data));
    }

    public function testSearchEngineWithMultipleQueries(): void
    {
        $criteria = Criteria::fromQueryParams('fruits', ['name' => 'a', 'color' => 'yellow']);

        $c = $this->collection;
        $b = new FilterBuilder();
        $e = new MongoDbSearchEngine($b, $c);

        $e->add(new FruitNameBuilder())
            ->add(new FruitColorBuilder())
        ;

        $result = $e->match($criteria);

        self::assertEquals(3, $result->count());
    }

    public function testPaginator(): void
    {
        $criteria = Criteria::fromQueryParams('fruits', ['name' => 'a', 'color' => 'yellow', 'page' => 1, 'limit' => 2]);

        $c = $this->collection;
        $b = new FilterBuilder();
        $e = new MongoDbSearchEngine($b, $c);

        $e->add(new FruitNameBuilder())
            ->add(new FruitColorBuilder())
        ;

        $result = $e->match($criteria);

        $paginator = new ResultPaginator($result, $criteria->paginating);

        self::assertEquals(3, $paginator->count());
        self::assertEquals(2, $paginator->getTotalPages());
        self::assertTrue($paginator->hasMore());
        self::assertEquals(2, $paginator->getNextPage());
    }

    private function createClient(): Client
    {
        $client = new Client(
            'mongodb://mongo-server:27017',
            [
                'username' => $_ENV['MONGODB_USER'],
                'password' => $_ENV['MONGODB_PASSWORD'],
                'authSource' => 'test',
            ]
        );

        return $client;
    }
}
