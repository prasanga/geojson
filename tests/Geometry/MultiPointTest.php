<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use PHPUnit\Framework\TestCase;

class MultiPointTest extends TestCase
{
    public function testMultiPointIsCreatedCorrectly()
    {
        $multiPoint = new MultiPoint([
            [0, 0], [10, 10, 10], [15, 15]
        ]);
        $coordinates = $multiPoint->getCoordinates();
        $this->assertEquals([
            [0, 0], [10, 10, 10], [15, 15]
        ], $coordinates);

        $positions = $multiPoint->getPositions();
        $this->assertEquals([
            new Position(0, 0),
            new Position(10, 10, 10),
            new Position(15, 15),
        ], $positions);
    }

    public function testMultiPointIsCreatedCorrectlyFromPoints()
    {
        $multiPoint = new MultiPoint([
            [-3, -6],
            new Point([0, 0, 0]),
            [-7, -3, -3],
            new Point([10, 10]),
            new Point([30, 50, 10]),
        ]);
        $coordinates = $multiPoint->getCoordinates();
        $this->assertEquals([
            [-3, -6],
            [0, 0, 0],
            [-7, -3, -3],
            [10, 10],
            [30, 50, 10],
        ], $coordinates);

        $positions = $multiPoint->getPositions();
        $this->assertEquals([
            new Position(-3, -6),
            new Position(0, 0, 0),
            new Position(-7, -3, -3),
            new Position(10, 10),
            new Position(30, 50, 10),
        ], $positions);
    }

    /**
     * @dataProvider invalidMultiPointCoordinatesProvider
     */
    public function testMultiPointValidatesCoordinateValues($invalidCoordinates)
    {
        $this->expectException('InvalidArgumentException');
        new MultiPoint($invalidCoordinates);
    }

    public function invalidMultiPointCoordinatesProvider()
    {
        return [
            'empty coordinates' => [
                [],
            ],
            'coordinates without point values' => [
                [
                    [],
                ],
            ],
            'coordinates with missing values in point' => [
                [
                    [1],
                ],
            ],
            'coordinates with boolean value in point' => [
                [
                    [1, false],
                ],
            ],
            'coordinates with null value in point' => [
                [
                    [1, null],
                ],
            ],
            'coordinates with string value in point' => [
                [
                    [1, 2, '4'],
                ],
            ],
            'coordinates with multiple points and empty point values' => [
                [
                    [1, 2, null], []
                ],
            ],
            'coordinates with multiple points and missing point values' => [
                [
                    [1, 2, null], [1]
                ],
            ],
            'coordinates with invalid objects' => [
                [
                    new Position(1, 1)
                ],
            ],
        ];
    }

    public function testMultiPointJsonSerializationIsCorrect()
    {
        $multiPoint = new MultiPoint([
            [0, 0, 0],
            new Point([10, 10]),
            new Point([30, 50, 10]),
        ]);
        $multiPointJson = json_encode($multiPoint);
        $this->assertJson($multiPointJson);
        $this->assertEquals('{"type":"MultiPoint","coordinates":[[0,0,0],[10,10],[30,50,10]]}', $multiPointJson);
    }
}
