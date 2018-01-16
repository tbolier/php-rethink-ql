<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
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

    /**
     * @throws \Exception
     */
    public function testInsert()
    {
        $res = $this->manager->createQueryBuilder()
                             ->table('testTable')
                             ->insert([
                                 [
                                     'documentId'  => 1,
                                     'title'       => 'Test document',
                                     'description' => 'My first document.',
                                 ],
                             ])
                             ->execute($this->connection);

        $this->assertObStatus(['inserted' => 1], $res);
    }

    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        $res = $this->manager->createQueryBuilder()
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
                             ])
                             ->execute($this->connection);

        $this->assertObStatus(['replaced' => 1], $res);

    }

    /**
     * @param $status
     * @param $data
     *
     * @throws \Exception
     */
    protected function assertObStatus($status, $data)
    {
        $res      = [];
        $statuses = [
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted',
        ];
        $data = new ArrayObject($data);

        foreach ($statuses as $s) {
            $status[$s] = isset($status[$s]) ? $status[$s] : 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = isset($data[$s]) ? $data[$s] : 0;
        }

        static::assertEquals($status, $res);
    }

    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        $res = $this->manager->createQueryBuilder()
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
                             ])
                             ->execute($this->connection);

        $this->assertObStatus(['replaced' => 1], $res);

    }

    /**
     * @param $status
     * @param $data
     *
     * @throws \Exception
     */
    protected function assertObStatus($status, $data)
    {
        $res      = [];
        $statuses = [
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted',
        ];
        $data = new ArrayObject($data);

        foreach ($statuses as $s) {
            $status[$s] = isset($status[$s]) ? $status[$s] : 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = isset($data[$s]) ? $data[$s] : 0;
        }

        static::assertEquals($status, $res);
    }
}
