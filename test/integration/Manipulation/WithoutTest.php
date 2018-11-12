<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class WithoutTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testWithoutResult()
    {
        $this->insertDocumentWithNumber(1, 1);

        /** @var ResponseInterface $cursor */
        $response = $this->r()
            ->table('tabletest')
            ->get(1)
            ->without('title', 'number')
            ->run();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertCount(2, $response->getData());
        $this->assertArrayNotHasKey('title', $response->getData());
        $this->assertArrayNotHasKey('number', $response->getData());
    }
}
