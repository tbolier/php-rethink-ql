<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Query\Row;
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

        $this->assertInstanceOf(Row::class, $row);
        $this->assertArraySubset($this->equalsParsedQuery(TermType::GT, $dateTime), $row->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testRowWithDateEqualLogic(): void
    {
        $this->insertDocumentWithDate(1, new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $dateTime = (new \DateTime('now'))->format(\DateTime::ATOM);
        $row = $this->r()->row('date')->eq($dateTime);

        $this->assertInstanceOf(Row::class, $row);
        $this->assertArraySubset($this->equalsParsedQuery(TermType::EQ, $dateTime), $row->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testRowWithDateNotEqualLogic(): void
    {
        $this->insertDocumentWithDate(1, new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $dateTime = (new \DateTime('now'))->format(\DateTime::ATOM);
        $row = $this->r()->row('date')->ne($dateTime);

        $this->assertInstanceOf(Row::class, $row);
        $this->assertArraySubset($this->equalsParsedQuery(TermType::NE, $dateTime), $row->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testRowWithDateMultipleAndLogic(): void
    {
        $yesterday = new \DateTime('-1 days');
        $tomorrow = new \DateTime('+1 days');
        $this->insertDocumentWithDate(1, $yesterday);
        $this->insertDocumentWithDate(2, $tomorrow);

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->eq($yesterday->format(\DateTime::ATOM))->and(
            $this->r()->row('date')->ne($tomorrow->format(\DateTime::ATOM))->and(
                $this->r()->row('id')->gt(0)->and(
                    $this->r()->row('id')->lt(3)
                )
            )
        );

        $this->assertInstanceOf(Row::class, $row);
        $this->assertArraySubset(
            [
                TermType::FUNC,
                [
                    [TermType::MAKE_ARRAY, [TermType::DATUM]],
                    [
                        TermType::AND,
                        [
                            [
                                TermType::EQ,
                                [
                                    [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'date']],
                                    $yesterday->format(\DateTime::ATOM),
                                ],
                            ],
                            [
                                TermType::AND,
                                [
                                    [
                                        TermType::NE,
                                        [
                                            [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'date']],
                                            $tomorrow->format(\DateTime::ATOM),
                                        ],
                                    ],
                                    [
                                        TermType::AND,
                                        [
                                            [
                                                TermType::GT,
                                                [
                                                    [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'id']],
                                                    0,
                                                ],
                                            ],
                                            [
                                                TermType::LT,
                                                [
                                                    [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'id']],
                                                    3,
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $row->toArray()
        );
    }

    /**
     * @throws \Exception
     */
    public function testRowWithDateMultipleOrLogic(): void
    {
        $yesterday = new \DateTime('-1 days');
        $tomorrow = new \DateTime('+1 days');
        $this->insertDocumentWithDate(1, $yesterday);
        $this->insertDocumentWithDate(2, $tomorrow);

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->eq($yesterday->format(\DateTime::ATOM))->or(
            $this->r()->row('date')->ne($tomorrow->format(\DateTime::ATOM))->or(
                $this->r()->row('id')->lt(3)->or(
                    $this->r()->row('id')->gt(0)
                )
            )
        );

        $this->assertInstanceOf(Row::class, $row);
        $this->assertArraySubset(
            [
                TermType::FUNC,
                [
                    [TermType::MAKE_ARRAY, [TermType::DATUM]],
                    [
                        TermType::OR,
                        [
                            [
                                TermType::EQ,
                                [
                                    [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'date']],
                                    $yesterday->format(\DateTime::ATOM),
                                ],
                            ],
                            [
                                TermType::OR,
                                [
                                    [
                                        TermType::NE,
                                        [
                                            [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'date']],
                                            $tomorrow->format(\DateTime::ATOM),
                                        ],
                                    ],
                                    [
                                        TermType::OR,
                                        [
                                            [
                                                TermType::LT,
                                                [
                                                    [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'id']],
                                                    3,
                                                ],
                                            ],
                                            [
                                                TermType::GT,
                                                [
                                                    [TermType::GET_FIELD, [[TermType::IMPLICIT_VAR, []], 'id']],
                                                    0,
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $row->toArray()
        );
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
