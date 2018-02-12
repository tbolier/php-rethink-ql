<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\ResponseInterface;

class CursorTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testCursor()
    {
        $this->insertDocuments();

        $response = $this->r()
            ->table('tabletest')
            ->count()
            ->run();

        $this->assertEquals(1000, $response->getData());
    }

    /**
     * @return ResponseInterface
     * @throws \Exception
     */
    private function insertDocuments(): ResponseInterface
    {
        $documents = [];

        for ($i = 1; $i <= 1000; $i++) {
            $documents[] = [
                'id' => $i,
                'title' => 'Test document',
                'description' => 'My first document.',
            ];
        }

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->insert($documents)
            ->run();

        $this->assertEquals(1000, $res->getData()['inserted']);

        return $res;
    }
}
