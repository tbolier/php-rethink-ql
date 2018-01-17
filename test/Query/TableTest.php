<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
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

        $this->manager = new Manager($this->createConnection('phpunit_default')->connect());
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
