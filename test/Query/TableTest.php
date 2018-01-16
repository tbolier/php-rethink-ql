<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use Mockery;
use TBolier\RethinkConnect\Connection\Connection;
use TBolier\RethinkConnect\Connection\ConnectionInterface;
use TBolier\RethinkConnect\Connection\OptionsInterface;
use TBolier\RethinkConnect\Document\Manager;
use TBolier\RethinkConnect\Document\ManagerInterface;
use TBolier\RethinkConnect\Test\BaseTestCase;

class TableTest extends BaseTestCase
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var ManagerInterface
     */
    private $manager;

    public function setUp()
    {
        parent::setUp();

        /** @var  OptionsInterface $optionsMock */
        $optionsMock = Mockery::mock(OptionsInterface::class);

        $this->connection = new Connection($optionsMock);

        $this->connection->connect();

        $this->manager = new Manager($this->connection);
    }

    public function testInsert()
    {
        $this->manager->createQueryBuilder()
                      ->table('testTable')
                      ->insert([
                          [
                              'documentId'  => 1,
                              'title'       => 'Test document',
                              'description' => 'My first document.',
                          ],
                      ]);
    }

    public function testUpdate()
    {
        $this->manager->createQueryBuilder()
                      ->table('testTable')
                      ->get([
                          'documentId'  => 1,
                          'title'       => 'Test document',
                          'description' => 'My first document.',
                      ])
                      ->update([
                          [
                              'documentId'  => 1,
                              'title'       => 'Test new document',
                              'description' => 'My second document.',
                          ],
                      ]);
    }
}
