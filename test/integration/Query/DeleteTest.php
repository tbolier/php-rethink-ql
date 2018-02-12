<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class DeleteTest extends BaseTestCase
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
    public function testDeleteDocument()
    {
        $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->run();

        /** @var ResponseInterface $count */
        $count = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->count()
            ->run();

        $this->assertInternalType('int', $count->getData());

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count->getData()], $res->getData());
    }
}
