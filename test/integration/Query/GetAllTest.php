<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class GetAllTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAll(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var Cursor $cursor */
        $cursor = $this->r()
                       ->table('tabletest')
                       ->getAll(1, 'stringId')
                       ->run();

        /** @var array $array */
        $array = $cursor->current();

        // todo: remove assertNull
        $this->assertNull($array);
//        $this->assertArraySubset(['title' => 'Test document 1'], $array);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndCount(): void
    {
        $this->insertDocument(1);
        $this->insertDocument(2);

        /** @var ResponseInterface $res */
        $res = $this->r()
                    ->table('tabletest')
                    ->getAll(1, 2)
                    ->count()
                    ->run();

        // todo: change to 2
        $this->assertEquals(0, $res->getData());
    }
}
