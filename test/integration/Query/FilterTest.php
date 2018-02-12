<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class FilterTest extends BaseTestCase
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
    public function testFilter()
    {
        $this->insertDocument(1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertInternalType('array', $cursor->current());
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
