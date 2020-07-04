<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use PHPUnit\Framework\TestCase;

class LineStringTest extends TestCase
{
    public function testLineStringIsCreatedCorrectly()
    {
        $lineString = new LineString([[1.1, 1.1], [10.1, 10]]);
        $coordinates = $lineString->getCoordinates();
        $this->assertEquals([[1.1, 1.1], [10.1, 10]], $coordinates);

        $positions = $lineString->getPositions();
        $this->assertContainsOnlyInstancesOf(Position::class, $positions);
        $this->assertCount(2, $positions);
        $this->assertEquals([1.1, 1.1], $positions[0]->toArray());
        $this->assertEquals([10.1, 10], $positions[1]->toArray());

        $lineString = new LineString([[1.1, 0, 3.0], [10.1, 10, 5.3], [45.2, 87.3]]);
        $coordinates = $lineString->getCoordinates();
        $this->assertEquals([[1.1, 0, 3.0], [10.1, 10, 5.3], [45.2, 87.3]], $coordinates);

        $positions = $lineString->getPositions();
        $this->assertContainsOnlyInstancesOf(Position::class, $positions);
        $this->assertCount(3, $positions);
        $this->assertEquals([1.1, 0, 3.0], $positions[0]->toArray());
        $this->assertEquals([10.1, 10, 5.3], $positions[1]->toArray());
        $this->assertEquals([45.2, 87.3], $positions[2]->toArray());
    }

    public function testLineStringIsCreatedCorrectlyFromPositions()
    {
        $lineString = new LineString([
            [5, 10],
            new Position(54, 6, 21),
            [3, 0, 0],
            new Position(0, 0, 23),
        ]);
        $coordinates = $lineString->getCoordinates();
        $this->assertEquals([
            [5, 10],
            [54, 6, 21],
            [3, 0, 0],
            [0, 0, 23],
        ], $coordinates);
        $positions = $lineString->getPositions();
        $this->assertEquals([
            new Position(5, 10),
            new Position(54, 6, 21),
            new Position(3, 0, 0),
            new Position(0, 0, 23),
        ], $positions);
    }

    /**
     * @dataProvider invalidCoordinatesProvider
     */
    public function testLineStringValidatesCoordinateValues($invalidCoordinates)
    {
        $this->expectException('InvalidArgumentException');
        $lineString = new LineString($invalidCoordinates);
    }

    public function invalidCoordinatesProvider()
    {
        return [
            'empty coordinates' => [
                [],
            ],
            'only 1 position set' => [
                [
                    [1, 2],
                ],
            ],
            'missing latitude value in position' => [
                [
                    [1],
                    [2, 4],
                ],
            ],
            'string value in position' => [
                [
                    [1.1, 2.2],
                    [2.2, '4'],
                ],
            ],
            'null value in position' => [
                [
                    new Position(2, 3),
                    [6, null],
                ],
            ],
            'boolean value in position' => [
                [
                    [2, false],
                    [6, 4.4],
                ],
            ],
            'boolean value for altitude' => [
                [
                    [2, 3],
                    [6, 5, false],
                ],
            ],
        ];
    }

    public function testLineStringJsonSerializationIsCorrect()
    {
        $lineString = new LineString([[1.1, 1.1], [10.1, 10]]);
        $lineStringJson = json_encode($lineString);
        $this->assertJson($lineStringJson);
        $this->assertEquals('{"type":"LineString","coordinates":[[1.1,1.1],[10.1,10]]}', $lineStringJson);

        $lineString = new LineString([[1.1, 1.1], [10.1, 10, 5.4], [0, 0]]);
        $lineStringJson = json_encode($lineString);
        $this->assertJson($lineStringJson);
        $this->assertEquals('{"type":"LineString","coordinates":[[1.1,1.1],[10.1,10,5.4],[0,0]]}', $lineStringJson);
    }
}
