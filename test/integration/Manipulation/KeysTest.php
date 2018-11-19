<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class KeysTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testKeysResult()
    {
        $this->insertDocumentWithNumber(1, 1);

        /** @var ResponseInterface $response */
        $response = $this->r()
            ->table('tabletest')
            ->get(1)
            ->keys()
            ->run();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertCount(4, $response->getData());
    }
}
