<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Transformation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class SkipTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testSkip(): void
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->skip(2)
            ->run();

        $this->assertCount(3, $cursor);
    }
}
