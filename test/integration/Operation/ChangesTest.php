<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class ChangesTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testChangesCreate(): void
    {
        /** @var Cursor $feed */
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
        foreach ($feed as $change) {
            $old_val = $change['old_val'];
            $new_val = $change['new_val'];

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
    public function testChangesUpdates(): void
    {
        /** @var Cursor $feed */
        $feed = $this->r()
            ->table('tabletest')
            ->changes()
            ->run();

        $this->insertDocument(777);

        $this->r()->table('tabletest')->filter(['id' => 777])->update(['description' => 'cool!'])->run();

        $i = 777;
        foreach ($feed as $change) {
            $old_val = $change['old_val'];
            $new_val = $change['new_val'];

            if ($old_val !== null) {
                $this->assertEquals('A document description.', $old_val['description']);
                $this->assertEquals('cool!', $new_val['description']);

                break;
            }

            $this->assertEmpty($old_val);
            $this->assertEquals($i, $new_val['id']);
        }
    }

    /**
     * @throws \Exception
     */
    public function testChangesWithOptions(): void
    {
        /** @var Cursor $feed */
        $feed = $this->r()
            ->table('tabletest')
            ->changes(['squash' => true])
            ->run();

        $this->insertDocument(1);
        $this->r()->table('tabletest')->filter(['id' => 1])->update(['description' => 'cool!'])->run();

        $change = $feed->current();
        $old_val = $change['old_val'];
        $new_val = $change['new_val'];

        $this->assertEmpty($old_val);
        $this->assertEquals(1, $new_val['id']);
        $this->assertEquals('cool!', $new_val['description']);
    }

    /**
     * @throws \Exception
     */
    public function testChangesCreateWithFilter(): void
    {
        /** @var Cursor $feed */
        $feed = $this->r()
            ->table('tabletest')
            ->filter(['id' => 4])
            ->changes()
            ->run();

        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        $change = $feed->current();
        $old_val = $change['old_val'];
        $new_val = $change['new_val'];

        $this->assertEmpty($old_val);
        $this->assertEquals(4, $new_val['id']);
    }
}
