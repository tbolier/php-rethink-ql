<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\ResponseInterface;

class ChangesTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testChangesCreate()
    {
        set_time_limit(5);

        $feed = $this->r()
            ->table('tabletest')
            ->changes()
            ->run();

        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $i = 1;
        $old_val = $new_val = [];
        foreach ($feed as $change) {
            extract($change);

            $this->assertEmpty($old_val);
            $this->assertEquals($i, $new_val['id']);

            if ($i === 5) {
                break;
            }

            $i++;
        }
    }

    /**
     * @throws \Exception
     */
    public function testChangesUpdates()
    {
        set_time_limit(5);

        $feed = $this->r()
            ->table('tabletest')
            ->changes()
            ->run();

        $this->insertDocument(777);

        $this->r()->table('tabletest')->filter(['id' => 777])->update(['description' => 'cool!'])->run();

        /** @var ResponseInterface $res */
        $i = 777;
        $old_val = $new_val = [];
        foreach ($feed as $change) {
            extract($change);

            if ($old_val !== null) {
                $this->assertEquals('A document description.', $old_val['description']);
                $this->assertEquals('cool!', $new_val['description']);

                break;
            } else {
                $this->assertEmpty($old_val);
                $this->assertEquals($i, $new_val['id']);
            }
        }
    }
}
