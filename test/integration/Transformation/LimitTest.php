<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Transformation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class LimitTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testLimit(): void
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->limit(2)
            ->run();

        $this->assertCount(2, $cursor);
    }

    public function testLimitWithMultipleTransformations(): void
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $cursor */
        $response = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->orderBy($this->r()->desc('id'))
            ->skip(1)
            ->limit(2)
            ->run();

        $this->assertCount(2, $response->getData());
    }
}
