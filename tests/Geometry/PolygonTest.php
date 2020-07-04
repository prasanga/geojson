<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use PHPUnit\Framework\TestCase;

class PolygonTest extends TestCase
{
    public function testPolygonIsCreatedCorrectly()
    {
        $polygon = new Polygon([
            [
                [0, 3], [5, 5], [6, 7], [0, 3],
            ],
        ]);
        $coordinates = $polygon->getCoordinates();
        $this->assertEquals([
            [
                [0, 3], [5, 5], [6, 7], [0, 3]
            ],
        ], $coordinates);
        
        $positions = $polygon->getPositions();
        $this->assertEquals([
            [
                new Position(0, 3),
                new Position(5, 5),
                new Position(6, 7),
                new Position(0, 3),
            ],
        ], $positions);
    }

    public function testPolygonWithMultipleLinearRingsIsCreatedCorrectly()
    {
        $polygonWithMultipleRings = new Polygon([
            [
                [2, 5], [5, 6], [8, 9], [2, 5],
            ],
            [
                [7, 3], [3, 8], [2, 0], [12, 54, 65], [7, 3],
            ],
        ]);
        $coordinates = $polygonWithMultipleRings->getCoordinates();
        $this->assertEquals([
            [
                [2, 5], [5, 6], [8, 9], [2, 5],
            ],
            [
                [7, 3], [3, 8], [2, 0], [12, 54, 65], [7, 3],
            ],
        ], $coordinates);
        $positions = $polygonWithMultipleRings->getPositions();
        $this->assertEquals([
            [
                new Position(2, 5),
                new Position(5, 6),
                new Position(8, 9),
                new Position(2, 5),
            ],
            [
                new Position(7, 3),
                new Position(3, 8),
                new Position(2, 0),
                new Position(12, 54, 65),
                new Position(7, 3),
            ],
        ], $positions);
    }

    public function testPolygonIsCreatedCorrectlyFromLineStrings()
    {
        $polygon = new Polygon([
            [
                [3, 5], [7, 3], [6, 2], [77, 32], [3, 5],
            ],
            new LineString([
                [5, 8], [23, 76], [4, 23], [5, 8],
            ])
        ]);
        $coordinates = $polygon->getCoordinates();
        $this->assertEquals([
            [
                [3, 5], [7, 3], [6, 2], [77, 32], [3, 5],
            ],
            [
                [5, 8], [23, 76], [4, 23], [5, 8],
            ],
        ], $coordinates);
        $positions = $polygon->getPositions();
        $this->assertEquals([
            [
                new Position(3, 5),
                new Position(7, 3),
                new Position(6, 2),
                new Position(77, 32),
                new Position(3, 5),
            ],
            [
                new Position(5, 8),
                new Position(23, 76),
                new Position(4, 23),
                new Position(5, 8),
            ],
        ], $positions);
    }

    /**
     * @dataProvider invalidPolygonCoordinateValuesProvider
     */
    public function testPolygonValidatesCoordinateValues($coordinates)
    {
        $this->expectException('InvalidArgumentException');
        new Polygon($coordinates);
    }

    public function invalidPolygonCoordinateValuesProvider()
    {
        return [
            'empty coordinates' => [
                [
                    [],
                ]
            ],
            'polygon with single linear ring without 4 positions' => [
                [
                    [
                        [2, 3], [5, 5], [6, 7],
                    ],
                ],
            ],
            'polygon with multiple linear rings without 4 positions' => [
                [
                    [
                        [2, 3], [5, 5], [6, 7], [2, 3]
                    ],
                    [
                        [2, 3], [5, 5], [6, 7],
                    ],
                ],
            ],
            'polygon with multiple linear rings with empty coordinates' => [
                [
                    [
                        [2, 3], [5, 5], [6, 7], [2, 3]
                    ],
                    [],
                    [
                        [2, 8], [5, 3], [6, 7], [2, 8]
                    ],
                ],
            ],
            'polygon without equivalent start and end positions' => [
                [
                    [
                        [10, 10], [20, 20], [40, 40], [0, 0], [50, 50]
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider validPolygonCoordinatesAndJsonProvider
     */
    public function testPolygonJsonSerializationIsCorrect($coordinates, $expectedJson)
    {
        $polygon = new Polygon($coordinates);
        $polygonJson = json_encode($polygon);
        $this->assertEquals($expectedJson, $polygonJson);
    }

    public function validPolygonCoordinatesAndJsonProvider()
    {
        return [
            [
                [
                    [
                        [0, 3], [5, 5], [6, 7], [0, 3],
                    ],
                ],
                '{"type":"Polygon","coordinates":[[[0,3],[5,5],[6,7],[0,3]]]}',
            ],
            [
                [
                    [
                        [2, 5], [5, 6], [8, 9], [2, 5],
                    ],
                    [
                        [7, 3], [3, 8], [2, 0], [5, 9], [7, 3],
                    ],
                ],
                '{"type":"Polygon","coordinates":[[[2,5],[5,6],[8,9],[2,5]],[[7,3],[3,8],[2,0],[5,9],[7,3]]]}',
            ],
        ];
    }
}
