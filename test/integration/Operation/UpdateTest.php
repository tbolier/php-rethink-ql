<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\ResponseInterface;

class UpdateTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        $this->insertDocument(1);

        $this->r()
            ->table('tabletest')
            ->insert(['title' => 'Update document'])
            ->run();

        /** @var ResponseInterface $count */
        $count = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Update document'])
            ->count()
            ->run();

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Update document'])
            ->update(['title' => 'Updated document'])
            ->run();

        $this->assertObStatus(['replaced' => $count->getData()], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testFilterUpdate()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['id' => 5])
            ->update(['title' => 'Updated document'])
            ->run();

        $this->assertObStatus(['replaced' => 1], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testFilterUpdateWithMultipleDocuments()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->update(['title' => 'Updated document'])
            ->run();

        $this->assertObStatus(['replaced' => 5], $res->getData());
    }
}
