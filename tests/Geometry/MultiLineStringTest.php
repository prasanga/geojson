<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use PHPUnit\Framework\TestCase;

class MultiLineStringTest extends TestCase
{
    public function testMultiLineStringIsCreatedCorrectly()
    {
        $multiLineString = new MultiLineString([
            [
                [0, 0, 0], [1, 1], [5, 6, 7]
            ]
        ]);
        $coordinates = $multiLineString->getCoordinates();
        $this->assertEquals([
            [
                [0, 0, 0], [1, 1], [5, 6, 7]
            ]
        ], $coordinates);
        $positions = $multiLineString->getPositions();
        $this->assertEquals([
            [
                new Position(0, 0, 0),
                new Position(1, 1),
                new Position(5, 6, 7),
            ]
        ], $positions);
    }

    public function testMultiLineStringIsCreatedCorrectlyFromLineStrings()
    {
        $multiLineString = new MultiLineString([
            [[0, 0, 0], [1, 1, 1], [2, 2]],
            new LineString([[10, 2, 5], [4, 6], [1, 77]]),
            [[4, 5], [4, 7], [5, 5, 8]],
            new LineString([[10, 86], [32, 76]]),
        ]);
        $coordinates = $multiLineString->getCoordinates();
        $this->assertEquals([
            [[0, 0, 0], [1, 1, 1], [2, 2]],
            [[10, 2, 5], [4, 6], [1, 77]],
            [[4, 5], [4, 7], [5, 5, 8]],
            [[10, 86], [32, 76]],
        ], $coordinates);
        $positions = $multiLineString->getPositions();
        $this->assertEquals([
            [new Position(0, 0, 0), new Position(1, 1, 1), new Position(2, 2)],
            [new Position(10, 2, 5), new Position(4, 6), new Position(1, 77)],
            [new Position(4, 5), new Position(4, 7), new Position(5, 5, 8)],
            [new Position(10, 86), new Position(32, 76)],
        ], $positions);
    }

    /**
     * @dataProvider invalidMultiLineStringCoordinatesProvider
     */
    public function testMultiLineStringValidatesCoordinates($invalidCoordinates)
    {
        $this->expectException('InvalidArgumentException');
        $multiLineString = new MultiLineString($invalidCoordinates);
    }

    public function invalidMultiLineStringCoordinatesProvider()
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
                    [[1, 0]],
                ],
            ],
            [
                [
                    [[1, 0], [0, '0']],
                ],
            ],
            [
                [
                    new Point([0, 0]),
                ],
            ],
            [
                [
                    [new Point([0, 0]), new Point([1, 1])],
                ],
            ],
            [
                [
                    [[1, 0], [0, 4]],
                    [],
                ],
            ],
            [
                [
                    [[1, 0], [0, 4]],
                    [[0, 0], [1, 2], [3, 4, '5']],
                ],
            ],
        ];
    }

    public function testMultiLineStringJsonSerializationIsCorrect()
    {
        $multiLineString = new MultiLineString([
            new LineString([[0, 0], [1, 1], [5, 5]]),
            [[6, 4], [2, 6], [22, 2]]
        ]);
        $multiLineStringJson = json_encode($multiLineString);
        $this->assertJson($multiLineStringJson);
        $this->assertEquals(
            '{"type":"MultiLineString","coordinates":[[[0,0],[1,1],[5,5]],[[6,4],[2,6],[22,2]]]}',
            $multiLineStringJson
        );
    }
}
