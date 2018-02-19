<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\IntegrationTest\AbstractTestCase;
use TBolier\RethinkQL\Response\ResponseInterface;

abstract class AbstractTableTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $res = $this->r()->db()->tableList()->run();
        if (\is_array($res->getData()) && !\in_array('tabletest', $res->getData(), true)) {
            $this->r()->db()->tableCreate('tabletest')->run();
        }
    }

    public function tearDown()
    {
        $res = $this->r()->db()->tableList()->run();
        if (\is_array($res->getData()) && \in_array('tabletest', $res->getData(), true)) {
            $this->r()->db()->tableDrop('tabletest')->run();
        }

        parent::tearDown();
    }


    /**
     * @param int|string $id
     * @return ResponseInterface
     */
    protected function insertDocument($id): ResponseInterface
    {
        $res = $this->r()
            ->table('tabletest')
            ->insert([
                'id' => $id,
                'title' => 'Test document '.$id,
                'description' => 'A document description.',
            ])
            ->run();

        return $res;
    }
}
