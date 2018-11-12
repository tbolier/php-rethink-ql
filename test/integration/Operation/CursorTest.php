<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class CursorTest extends AbstractTableTest
{
    public function setUp()
    {
        parent::setUp();

        $res = $this->r()->db()->tableList()->run();
        if (\is_array($res->getData()) && !\in_array('tabletest_big', $res->getData(), true)) {
            $this->r()->db()->tableCreate('tabletest_big')->run();
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        $res = $this->r()->db()->tableList()->run();
        if (\is_array($res->getData()) && \in_array('tabletest_big', $res->getData(), true)) {
            $this->r()->db()->tableDrop('tabletest_big')->run();
        }
    }

    /**
     * @throws \Exception
     */
    public function testCursor()
    {
        $this->insertDocuments();

        $response = $this->r()
            ->table('tabletest')
            ->count()
            ->run();

        $this->assertEquals(1000, $response->getData());
    }

    /**
     * @throws \Exception
     */
    public function testCursorBigDocuments()
    {
        $data = [
            'Professor X' => 'Charles Francis Xavier',
            'Cyclops' => 'Scott Summers',
            'Iceman' => 'Robert Louis "Bobby" Drake',
            'Angel' => 'Warren Kenneth Worthington III',
            'Beast' => 'Henry Philip "Hank" McCoy',
            'Marvel Girl/Phoenix' => 'Jean Elaine Grey/Jean Elaine Grey-Summers',
            'Magnetrix/Polaris' => 'Lorna Sally Dane',
            'Nightcrawler' => 'Kurt Wagner',
            'Wolverine' => 'James "Logan" Howlett',
            'Storm' => 'Ororo Monroe',
            'Colossus' => 'Piotr Nikolaievitch "Peter" Rasputin',
            'Sprite/Ariel/Shadowcat' => 'Katherine Anne "Kitty" Pryde',
            'Rogue' => 'Anna Marie',
            'Phoenix/Marvel Girl/Prestige' => 'Rachel Anne Grey-Summers',
            'Psylocke' => 'Elizabeth "Betsy" Braddock',
            'Gambit' => 'Rémy Etienne LeBeau',
            'Jubilee' => 'Jubilation Lee',
            'Bishop' => 'Lucas Bishop',
        ];

        $this->insertBigDocuments($data);

        $cursor = $this->r()->table('tabletest_big')->run();

        $i = 0;
        foreach ($cursor as $document) {
            // Assert the document every 1000s documents.
            if ($i % 1000 === 0) {
                $this->assertArraySubset($data, $document);
            }
            $i++;
        }

        $this->assertEquals($i, $this->r()->table('tabletest_big')->count()->run()->getData());

        $this->assertInstanceOf(Cursor::class, $cursor);

        $this->r()->table('tabletest_big')->delete()->run();
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
                'id' => $i,
                'title' => 'Test document',
                'description' => 'My first document.',
            ];
        }

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->insert($documents)
            ->run();

        $this->assertEquals(1000, $res->getData()['inserted']);

        return $res;
    }

    /**
     * @param array $data
     * @return ResponseInterface
     * @throws \Exception
     */
    private function insertBigDocuments(array $data): ResponseInterface
    {
        $documents = [];
        for ($i = 1; $i <= 100; $i++) {
            $documents = [];

            for ($x = 1; $x <= 100; $x++) {
                $documents[] = $data;
            }
        }
        
        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest_big')
            ->insert($documents)
            ->run();

        $this->assertEquals(100, $res->getData()['inserted']);

        return $res;
    }
}
