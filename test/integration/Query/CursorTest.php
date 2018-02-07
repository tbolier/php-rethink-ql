<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\IntegrationTest\BaseTestCase;
use TBolier\RethinkQL\Response\ResponseInterface;

class CursorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!\in_array('cursortest', $this->r()->db()->tableList()->run()->getData()[0], true)) {
            $this->r()->db()->tableCreate('cursortest')->run();
        }
    }

    public function tearDown()
    {
        if (\in_array('cursortest', $this->r()->db()->tableList()->run()->getData()[0], true)) {
            $this->r()->db()->tableDrop('cursortest')->run();
        }

        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    public function testCursor()
    {
        $this->insertDocuments();

        $cursor = $this->r()
            ->table('cursortest')
            ->run();

        $this->assertCount(1000, $cursor);
    }

    /**
     * @return ResponseInterface
     * @throws \Exception
     */
    private function insertDocuments(): ResponseInterface
    {
        $documents = [];

        for ($i = 1; $i <= 1000; $i++) {
            $documents[] = [
                'documentId' => $i,
                'title' => 'Test document',
                'description' => 'My first document.',
            ];
        }

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('cursortest')
            ->insert([$documents])
            ->run();

        $this->assertEquals(1000, $res->getData()[0]['inserted']);

        return $res;
    }
}
