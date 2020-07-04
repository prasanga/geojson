<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use InvalidArgumentException;
use TypeError;

class Point extends Geometry
{
    public function __construct(array $coordinates)
    {
        try {
            if (count($coordinates) < 2) {
                throw new InvalidArgumentException(
                    sprintf(
                        "Invalid coordinate count. %s requires at least %d coordinates",
                        $this->getType(),
                        2
                    )
                );
            }
            $this->coordinates = new Position($coordinates[0], $coordinates[1], $coordinates[2] ?? null);
        } catch (TypeError $e) {
            throw new InvalidArgumentException(
                sprintf("Invalid coordinate arguments: %s", var_export($coordinates, true))
            );
        }
    }

    public function getCoordinates(): array
    {
        return $this->coordinates->toArray();
    }

    public function getPositions(): array
    {
        return [$this->coordinates];
    }
}
