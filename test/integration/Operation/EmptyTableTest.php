<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\ResponseInterface;

class EmptyTableTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testEmptyTable()
    {
        /** @var ResponseInterface $count */
        $count = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Test document'])
            ->count()
            ->run();

        $this->assertInternalType('int', $count->getData());

        $res = $this->r()
            ->table('tabletest')
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count->getData()], $res->getData());
    }
}
