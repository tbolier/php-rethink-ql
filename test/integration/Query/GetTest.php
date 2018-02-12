<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class GetTest extends BaseTestCase
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
     * @return void
     * @throws \Exception
     */
    public function testGet(): void
    {
        $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'id' => 'foo',
                ],
            ])
            ->run();

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->get('foo')
            ->run();

        $this->assertEquals(['id' => 'foo'], $res->getData());
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
