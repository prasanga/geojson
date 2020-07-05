<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

class MultiPolygonTest extends \PHPUnit\Framework\TestCase
{
    public function testMultiPolygonIsCreatedCorrectlyFromArrayOfCoordinates()
    {
        $multiPolygon = new MultiPolygon([
            [
                [[0, 0], [10, 10], [20, 20], [0, 0]]
            ],
            [
                [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35]],
                [[30, 20], [20, 15], [20, 25], [30, 20]],
            ],
        ]);
        $this->assertEquals([
            [
                [[0, 0], [10, 10], [20, 20], [0, 0]]
            ],
            [
                [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35]],
                [[30, 20], [20, 15], [20, 25], [30, 20]],
            ],
        ], $multiPolygon->getCoordinates());
        $this->assertEquals([
            [
                [
                    new Position(0, 0),
                    new Position(10, 10),
                    new Position(20, 20),
                    new Position(0, 0),
                ]
            ],
            [
                [
                    new Position(20, 35),
                    new Position(10, 30),
                    new Position(10, 10),
                    new Position(30, 5),
                    new Position(45, 20),
                    new Position(20, 35),
                ],
                [
                    new Position(30, 20),
                    new Position(20, 15),
                    new Position(20, 25),
                    new Position(30, 20),
                ],
            ],
        ], $multiPolygon->getPositions());
    }

    public function testMultiPolygonIsCreatedCorrectlyFromPolygons()
    {
        $multiPolygon = new MultiPolygon([
            new Polygon([
                [
                    [0, 0], [10, 10], [20, 20], [0, 0],
                ]
            ]),
            new Polygon([
                [
                    [20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35],
                ],
                [
                    [30, 20], [20, 15], [20, 25], [30, 20],
                ],
            ]),
        ]);
        $this->assertEquals([
            [
                [[0, 0], [10, 10], [20, 20], [0, 0]]
            ],
            [
                [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35]],
                [[30, 20], [20, 15], [20, 25], [30, 20]],
            ],
        ], $multiPolygon->getCoordinates());
    }

    /**
     * @dataProvider invalidMultiPolygonDataProvider
     */
    public function testMultiPolygonValidatesCoordinates($invalidCoordinates)
    {
        $this->expectException('InvalidArgumentException');
        new MultiPolygon($invalidCoordinates);
    }

    public function invalidMultiPolygonDataProvider()
    {
        return [
            [
                [],
            ],
            [
                [
                    [],
                ],
            ],
            [
                [
                    [], [],
                ],
            ],
            [
                [], [],
            ],
            [
                [
                    [[0, 0], [10, 10], [20, 20], [0, 0]]
                ],
            ],
            [
                [
                    [
                        [[0, 0], [10, 10], [20, 20]]
                    ],
                ],
            ],
            [
                [
                    [
                        [[0, 0], [10, 10], [20, 20], [0, 0]]
                    ],
                    [
                        [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 20]],
                        [[30, 20], [20, 15], [20, 25], [30, 20]],
                    ],
                ],
            ],
            [
                [
                    new LineString([[30, 20], [20, 15], [20, 25], [30, 20]]),
                ],
            ],
        ];
    }

    public function testMultiPolygonJsonSerializationIsCorrect()
    {
        $multiPolygon = new MultiPolygon([
            [
                [[0, 0], [10, 10], [20, 20], [0, 0]]
            ],
            [
                [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35]],
                [[30, 20], [20, 15], [20, 25], [30, 20]],
            ],
        ]);
        $multiPolygonJsonString = json_encode($multiPolygon);
        $this->assertJson($multiPolygonJsonString);
        $this->assertEquals(
            '{"type":"MultiPolygon","coordinates":[[[[0,0],[10,10],[20,20],[0,0]]],[[[20,35],[10,30],[10,10],[30,5],[45,20],[20,35]],[[30,20],[20,15],[20,25],[30,20]]]]}',
            $multiPolygonJsonString
        );
    }
}
