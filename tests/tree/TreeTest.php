<?php

namespace marx\tests\tree;

use marx\tree\Tree;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class TreeTest extends TestCase
{
    public function testGenerate()
    {
        $cases = [
            [
                'list' => [
                    1 => ['id' => 1, 'parent_id' => 0],
                    2 => ['id' => 2, 'parent_id' => 0],
                    3 => ['id' => 3, 'parent_id' => 1],
                    4 => ['id' => 4, 'parent_id' => 2],
                    5 => ['id' => 5, 'parent_id' => 2],
                    6 => ['id' => 6, 'parent_id' => 1],
                    7 => ['id' => 7, 'parent_id' => 5],
                    8 => ['id' => 8, 'parent_id' => 6],
                ],
                'expected' => [
                    [
                        'id' => 1,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 3,
                                'parent_id' => 1,
                            ],
                            [
                                'id' => 6,
                                'parent_id' => 1,
                                'children' => [
                                    [
                                        'id' => 8,
                                        'parent_id' => 6,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 2,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 4,
                                'parent_id' => 2,
                            ],
                            [
                                'id' => 5,
                                'parent_id' => 2,
                                'children' => [
                                    [
                                        'id' => 7,
                                        'parent_id' => 5,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'list' => [
                    4 => ['id' => 4, 'parent_id' => 2],
                    2 => ['id' => 2, 'parent_id' => 0],
                    6 => ['id' => 6, 'parent_id' => 1],
                    7 => ['id' => 7, 'parent_id' => 5],
                    3 => ['id' => 3, 'parent_id' => 1],
                    5 => ['id' => 5, 'parent_id' => 2],
                    8 => ['id' => 8, 'parent_id' => 6],
                    1 => ['id' => 1, 'parent_id' => 0],
                ],
                'expected' => [
                    [
                        'id' => 2,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 4,
                                'parent_id' => 2,
                            ],
                            [
                                'id' => 5,
                                'parent_id' => 2,
                                'children' => [
                                    [
                                        'id' => 7,
                                        'parent_id' => 5,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 1,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 6,
                                'parent_id' => 1,
                                'children' => [
                                    [
                                        'id' => 8,
                                        'parent_id' => 6,
                                    ],
                                ],
                            ],
                            [
                                'id' => 3,
                                'parent_id' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        foreach ($cases as $case) {
            $result = (new Tree())->generate($case['list']);
            $this->assertEquals($case['expected'], $result);
        }
    }

    public function testGenerateNotIndexedByKey()
    {
        $cases = [
            [
                'list' => [
                    ['id' => 1, 'parent_id' => 0],
                    ['id' => 2, 'parent_id' => 0],
                    ['id' => 3, 'parent_id' => 1],
                    ['id' => 4, 'parent_id' => 2],
                    ['id' => 5, 'parent_id' => 2],
                    ['id' => 6, 'parent_id' => 1],
                    ['id' => 7, 'parent_id' => 5],
                    ['id' => 8, 'parent_id' => 6],
                ],
                'expected' => [
                    [
                        'id' => 1,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 3,
                                'parent_id' => 1,
                            ],
                            [
                                'id' => 6,
                                'parent_id' => 1,
                                'children' => [
                                    [
                                        'id' => 8,
                                        'parent_id' => 6,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 2,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 4,
                                'parent_id' => 2,
                            ],
                            [
                                'id' => 5,
                                'parent_id' => 2,
                                'children' => [
                                    [
                                        'id' => 7,
                                        'parent_id' => 5,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'list' => [
                    ['id' => 4, 'parent_id' => 2],
                    ['id' => 2, 'parent_id' => 0],
                    ['id' => 6, 'parent_id' => 1],
                    ['id' => 7, 'parent_id' => 5],
                    ['id' => 3, 'parent_id' => 1],
                    ['id' => 5, 'parent_id' => 2],
                    ['id' => 8, 'parent_id' => 6],
                    ['id' => 1, 'parent_id' => 0],
                ],
                'expected' => [
                    [
                        'id' => 2,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 4,
                                'parent_id' => 2,
                            ],
                            [
                                'id' => 5,
                                'parent_id' => 2,
                                'children' => [
                                    [
                                        'id' => 7,
                                        'parent_id' => 5,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 1,
                        'parent_id' => 0,
                        'children' => [
                            [
                                'id' => 6,
                                'parent_id' => 1,
                                'children' => [
                                    [
                                        'id' => 8,
                                        'parent_id' => 6,
                                    ],
                                ],
                            ],
                            [
                                'id' => 3,
                                'parent_id' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        foreach ($cases as $case) {
            $result = (new Tree())->generateNotIndexedByKey($case['list']);
            $this->assertEquals($case['expected'], $result);
        }
    }
}
