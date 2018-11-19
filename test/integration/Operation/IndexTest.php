<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\ResponseInterface;

class IndexTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testIndexCreate()
    {
        $this->insertDocument(1);

        $res = $this->r()
             ->table('tabletest')
             ->indexCreate('title')
             ->run();

        $this->assertObStatus(['created' => 1], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testIndexDrop()
    {
        $this->insertDocument(1);

        $this->r()
             ->table('tabletest')
             ->indexCreate('title')
             ->run();

        $res = $this->r()
                    ->table('tabletest')
                    ->indexDrop('title')
                    ->run();

        $this->assertObStatus(['dropped' => 1], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testIndexList()
    {
        $this->insertDocument(1);

        $this->r()
             ->table('tabletest')
             ->indexCreate('title')
             ->run();

        /** @var ResponseInterface $res */
        $res = $this->r()
                    ->table('tabletest')
                    ->indexList()
                    ->run();

        $this->assertEquals('title', $res->getData()[0]);
    }

    /**
     * @throws \Exception
     */
    public function testIndexRename()
    {
        $this->insertDocument(1);

        $this->r()
             ->table('tabletest')
             ->indexCreate('title')
             ->run();

        $res = $this->r()
                    ->table('tabletest')
                    ->indexRename('title', 'description')
                    ->run();

        $this->assertObStatus(['renamed' => 1], $res->getData());

        $res = $this->r()
                    ->table('tabletest')
                    ->indexList()
                    ->run();

        $this->assertEquals('description', $res->getData()[0]);
    }
}
