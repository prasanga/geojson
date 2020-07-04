<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use JsonSerializable;

class Position implements JsonSerializable
{
    private $lon = null;

    private $lat = null;
    
    private $alt = null;

    public function __construct(float $longitude, float $latitude, ?float $altitude = null)
    {
        $this->lon = $longitude;
        $this->lat = $latitude;
        $this->alt = $altitude;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        $values = [$this->lon, $this->lat];

        if ($this->alt !== null) {
            $values[] = $this->alt;
        }

        return $values;
    }

    public function getLongitude(): float
    {
        return $this->lon;
    }

    public function getLatitude(): float
    {
        return $this->lat;
    }

    public function getAltitude(): ?float
    {
        return $this->alt;
    }
}
