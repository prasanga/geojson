<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use JsonSerializable;

abstract class Geometry implements JsonSerializable
{
    protected $coordinates = null;

    public function getType(): string
    {
        $className = get_class($this);
        $className = explode('\\', $className);
        return end($className);
    }

    /**
     * Return coordinates.
     *
     * @return array
     */
    abstract public function getCoordinates(): array;

    /**
     * Return coordinates as an array of Position objects.
     *
     * @return Position[]
     */
    abstract public function getPositions(): array;

    public function jsonSerialize()
    {
        return [
            'type' => $this->getType(),
            'coordinates' => $this->getCoordinates(),
        ];
    }
}
