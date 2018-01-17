<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Document\Manager;
use TBolier\RethinkQL\Document\ManagerInterface;
use TBolier\RethinkQL\Test\BaseTestCase;

class TableTest extends BaseTestCase
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    public function setUp()
    {
        parent::setUp();

        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();

        $this->manager = new Manager($connection);
    }

    /**
     * @throws \Exception
     */
    public function testInsert()
    {
        $res = $this->manager->createQueryBuilder()
                             ->table('nl')
                             ->insert([
                                 [
                                     'documentId' => 1,
                                     'title' => 'Test document',
                                     'description' => 'My first document.',
                                 ],
                             ])
                             ->execute();

        $this->assertObStatus(['inserted' => 1], $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testCount()
    {
        $res = $this->manager->createQueryBuilder()
                             ->table('nl')
                             ->count()
                             ->execute();

        static::assertInternalType('int', $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testFilter()
    {
        $res = $this->manager->createQueryBuilder()
                             ->table('nl')
                             ->filter([
                                 [
                                     'title' => 'Test document',
                                 ],
                             ])
                             ->execute();

        static::assertInternalType('array', $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        static::markTestSkipped('Todo: create unit test for update');

        // Todo: create unit test for update.

        //$res = $this->manager->createQueryBuilder()
        //                     ->table('testTable')
        //                     ->get([
        //                         'documentId'  => 1,
        //                         'title'       => 'Test document',
        //                         'description' => 'My first document.',
        //                     ])
        //                     ->update([
        //                         [
        //                             'documentId'  => 1,
        //                             'title'       => 'Test new document',
        //                             'description' => 'My second document.',
        //                         ],
        //                     ])
        //                     ->execute();
        //
        //$this->assertObStatus(['replaced' => 1], $res[0]);

    }

    /**
     * @throws \Exception
     */
    public function testEmptyTable()
    {
        $count = $this->manager->createQueryBuilder()
                               ->table('nl')
                               ->filter([
                                   [
                                       'title' => 'Test document',
                                   ],
                               ])
                               ->count()
                               ->execute();

        $res = $this->manager->createQueryBuilder()
                             ->table('nl')
                             ->delete()
                             ->execute();

        $this->assertObStatus(['deleted' => $count[0]], $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testDeleteDocument()
    {
        $this->manager->createQueryBuilder()
                      ->table('nl')
                      ->insert([
                          [
                              'title' => 'Delete document',
                          ],
                      ])
                      ->execute();

        $count = $this->manager->createQueryBuilder()
                               ->table('nl')
                               ->filter([
                                   [
                                       'title' => 'Delete document',
                                   ],
                               ])
                               ->count()
                               ->execute();

        $res = $this->manager->createQueryBuilder()
                             ->table('nl')
                             ->filter([
                                 [
                                     'title' => 'Delete document',
                                 ],
                             ])
                             ->delete()
                             ->execute();

        $this->assertObStatus(['deleted' => $count[0]], $res[0]);
    }

    /**
     * @param $status
     * @param $data
     * @throws \Exception
     */
    protected function assertObStatus($status, $data)
    {
        $res = [];
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
            $status[$s] = $status[$s] ?? 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = $data[$s] ?? 0;
        }

        static::assertEquals($status, $res);
    }
}
