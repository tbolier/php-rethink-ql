<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\AbstractTestCase;

class DeleteTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testDeleteDocument()
    {
        $this->r()
            ->table('tabletest')
            ->insert([
                'title' => 'Delete document',
            ])
            ->run();

        /** @var ResponseInterface $count */
        $count = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Delete document'])
            ->count()
            ->run();

        $this->assertInternalType('int', $count->getData());

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Delete document'])
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count->getData()], $res->getData());
    }
}
