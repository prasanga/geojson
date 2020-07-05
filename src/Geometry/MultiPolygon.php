<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use InvalidArgumentException;

class MultiPolygon extends Geometry
{
    public function __construct(array $coordinates)
    {
        if (empty($coordinates)) {
            throw new InvalidArgumentException(
                sprintf("%s requires at least one set of Polygon coordinates", $this->getType())
            );
        }
        
        foreach ($coordinates as $polygon) {
            if (is_array($polygon)) {
                $polygon = new Polygon($polygon);
            }

            if (!$polygon instanceof Polygon) {
                throw new InvalidArgumentException(
                    sprintf("Invalid argument supplied: %s", var_export($polygon, true))
                );
            }

            $this->coordinates[] = $polygon;
        }
    }

    public function getCoordinates(): array
    {
        return array_map(
            function (Polygon $polygon) {
                return $polygon->getCoordinates();
            },
            $this->coordinates
        );
    }

    /**
     * @inheritDoc
     */
    public function getPositions(): array
    {
        return array_map(
            function (Polygon $polygon) {
                return $polygon->getPositions();
            },
            $this->coordinates
        );
    }
}
