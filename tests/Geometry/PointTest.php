<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testPointIsCreatedCorrectly()
    {
        $point = new Point([100.0, 200.1]);
        $pointCoordinates = $point->getCoordinates();
        $this->assertEquals([100.0, 200.1], $pointCoordinates);

        $pointPositions = $point->getPositions();
        $this->assertContainsOnlyInstancesOf(Position::class, $pointPositions);
        $this->assertCount(1, $pointPositions);
        $this->assertEquals(100.0, $pointPositions[0]->getLongitude());
        $this->assertEquals(200.1, $pointPositions[0]->getLatitude());
        $this->assertNull($pointPositions[0]->getAltitude());

        $pointIn3d = new Point([10.2, 50, 0.0]);
        $pointIn3dCoordinates = $pointIn3d->getCoordinates();
        $this->assertEquals([10.2, 50, 0.0], $pointIn3dCoordinates);

        $pointIn3dPositions = $pointIn3d->getPositions();
        $this->assertContainsOnlyInstancesOf(Position::class, $pointIn3dPositions);
        $this->assertCount(1, $pointIn3dPositions);
        $this->assertEquals(10.2, $pointIn3dPositions[0]->getLongitude());
        $this->assertEquals(50, $pointIn3dPositions[0]->getLatitude());
        $this->assertEquals(0.0, $pointIn3dPositions[0]->getAltitude());
    }

    public function testPointJsonSerializationIsCorrect()
    {
        $point = new Point([100.0, 200.1]);
        $pointJson = json_encode($point);
        $this->assertJson($pointJson);
        $this->assertEquals('{"type":"Point","coordinates":[100,200.1]}', $pointJson);

        $pointIn3d = new Point([10.2, 50, 122.8]);
        $pointIn3dJson = json_encode($pointIn3d);
        $this->assertJson($pointIn3dJson);
        $this->assertEquals('{"type":"Point","coordinates":[10.2,50,122.8]}', $pointIn3dJson);

        $zeroPoint = new Point([34, 56.3, 0.0]);
        $zeroPointJson = json_encode($zeroPoint);
        $this->assertJson($zeroPointJson);
        $this->assertEquals('{"type":"Point","coordinates":[34,56.3,0]}', $zeroPointJson);
    }

    /**
     * @dataProvider invalidCoordinatesProvider
     */
    public function testPointValidatesCoordinateValues($coordinates)
    {
        $this->expectException('InvalidArgumentException');
        new Point($coordinates);
    }

    public function invalidCoordinatesProvider()
    {
        return [
            [
                [],
            ],
            [
                [1],
            ],
            [
                [1, null],
            ],
            [
                [1.0, 2.2, '3.4'],
            ],
            [
                [1.0, false, 5],
            ],
            [
                [null, 3.3, 1],
            ],
            [
                [true, 3.3, 1],
            ],
        ];
    }
}
