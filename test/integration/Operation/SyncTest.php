<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\ResponseInterface;

class SyncTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testSync(): void
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $result */
        $result = $this->r()
            ->table('tabletest')
            ->sync()
            ->run();

        $this->assertEquals(['synced' => true], $result->getData());
    }
}
