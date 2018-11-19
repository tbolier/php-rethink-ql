<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class PluckTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testPluckResult()
    {
        $this->insertDocumentWithNumber(1, 1);

        /** @var ResponseInterface $response */
        $response = $this->r()
            ->table('tabletest')
            ->get(1)
            ->pluck('id', 'description')
            ->run();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertCount(2, $response->getData());
        $this->assertArrayHasKey('id', $response->getData());
        $this->assertArrayHasKey('description', $response->getData());
    }
}
