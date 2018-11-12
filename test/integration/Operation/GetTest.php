<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\ResponseInterface;

class GetTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testGet(): void
    {
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->get(1)
            ->run();

        /** @var array $array */
        $array = $res->getData();

        $this->assertArraySubset(['id' => 1], $array);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetNonExistingDocument(): void
    {
        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->get('bar')
            ->run();

        $this->assertEquals(null, $res->getData());
    }
}
