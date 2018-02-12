<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class EmptyTableTest extends BaseTestCase
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
    public function testEmptyTable()
    {
        /** @var ResponseInterface $count */
        $count = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->count()
            ->run();

        $this->assertInternalType('int', $count->getData());

        $res = $this->r()
            ->table('tabletest')
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count->getData()], $res->getData());
    }
}
