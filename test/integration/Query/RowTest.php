<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Query\Logic\FuncLogic;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class RowTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testRowWithDateGreaterThanLogic(): void
    {
        $this->insertDocumentWithDate(1, new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $dateTime = (new \DateTime('now'))->format(\DateTime::ATOM);
        $row = $this->r()->row('date')->gt($dateTime);

        $this->assertInstanceOf(FuncLogic::class, $row);
        $this->assertArraySubset($this->equalsParsedQuery(TermType::GT, $dateTime), $row->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testRowWithDateLowerThanLogic(): void
    {
        $this->insertDocumentWithDate(1, new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $dateTime = (new \DateTime('now'))->format(\DateTime::ATOM);
        $row = $this->r()->row('date')->lt($dateTime);

        $this->assertInstanceOf(FuncLogic::class, $row);
        $this->assertArraySubset($this->equalsParsedQuery(TermType::LT, $dateTime), $row->toArray());
    }

    /**
     * @param int $funcType
     * @param $dateTime
     * @return array
     */
    private function equalsParsedQuery(int $funcType, $dateTime): array
    {
        return [
            TermType::FUNC,
            [
                [
                    TermType::MAKE_ARRAY,
                    [
                        TermType::DATUM,
                    ],
                ],
                [
                    $funcType,
                    [
                        [
                            TermType::GET_FIELD,
                            [
                                [
                                    TermType::IMPLICIT_VAR,
                                    [],
                                ],
                                'date',
                            ],
                        ],
                        $dateTime,
                    ],
                ],
            ],
        ];
    }
}
