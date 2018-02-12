<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class CountTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!\in_array('tabletest', $this->r()->db()->tableList()->run()->getData(), true)) {
            $this->r()->db()->tableCreate('tabletest')->run();
        }
    }

    public function tearDown()
    {
        if (\in_array('tabletest', $this->r()->db()->tableList()->run()->getData(), true)) {
            $this->r()->db()->tableDrop('tabletest')->run();
        }

        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    public function testCount()
    {
        $this->insertDocument(1);

        $res = $this->r()
            ->table('tabletest')
            ->count()
            ->run();

        $this->assertInternalType('int', $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testFilterCount()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->count()
            ->run();

        $this->assertEquals(5, $res->getData());
    }

    /**
     * @param int $documentId
     * @return ResponseInterface
     */
    private function insertDocument(int $documentId): ResponseInterface
    {
        $res = $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'documentId' => $documentId,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
            ])
            ->run();

        return $res;
    }
}
