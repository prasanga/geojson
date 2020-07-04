<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public function testPositionIsCreatedCorrectly()
    {
        $position = new Position(10.1, 10.0);
        $this->assertEquals(10.1, $position->getLongitude());
        $this->assertEquals(10.0, $position->getLatitude());
        $this->assertNull($position->getAltitude());
        $this->assertEquals([10.1, 10], $position->toArray());

        $positionWithAltitude = new Position(10.1, 10.0, 53.5);
        $this->assertEquals(10.1, $positionWithAltitude->getLongitude());
        $this->assertEquals(10.0, $positionWithAltitude->getLatitude());
        $this->assertEquals(53.5, $positionWithAltitude->getAltitude());
        $this->assertEquals([10.1, 10, 53.5], $positionWithAltitude->toArray());

        $zeroPosition = new Position(0, 0, 0);
        $this->assertEquals(0, $zeroPosition->getLongitude());
        $this->assertEquals(0, $zeroPosition->getLatitude());
        $this->assertEquals(0, $zeroPosition->getAltitude());
        $this->assertEquals([0, 0, 0], $zeroPosition->toArray());
    }

    public function testPositionJsonSerializationIsCorrect()
    {
        $position = new Position(10.1, 10.0);
        $positionJson = json_encode($position);
        $this->assertJson($positionJson);
        $this->assertEquals('[10.1,10]', $positionJson);

        $positionWithAltitude = new Position(10.1, 10.0, 53.5);
        $positionWithAltitudeJson = json_encode($positionWithAltitude);
        $this->assertJson($positionWithAltitudeJson);
        $this->assertEquals('[10.1,10,53.5]', $positionWithAltitudeJson);

        $zeroPosition = new Position(0, 0, 0);
        $zeroPositionJson = json_encode($zeroPosition);
        $this->assertJson($zeroPositionJson);
        $this->assertEquals('[0,0,0]', $zeroPositionJson);
    }
}
