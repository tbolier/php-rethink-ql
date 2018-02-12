<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class UpdateTest extends BaseTestCase
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
    public function testUpdate()
    {
        $this->insertDocument(1);

        $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'title' => 'Update document',
                ],
            ])
            ->run();

        /** @var ResponseInterface $count */
        $count = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Update document',
                ],
            ])
            ->count()
            ->run();
        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Update document',
                ],
            ])
            ->update([
                [
                    'title' => 'Updated document',
                ],
            ])
            ->run();

        $this->assertObStatus(['replaced' => $count->getData()], $res->getData());
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
