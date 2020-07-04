<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use InvalidArgumentException;
use TypeError;

class MultiPoint extends Geometry
{
    public function __construct(array $coordinates)
    {
        try {
            if (empty($coordinates)) {
                throw new InvalidArgumentException(
                    sprintf("%s requires at least one set of coordinates", $this->getType())
                );
            }
            foreach ($coordinates as $point) {
                if (is_array($point)) {
                    $point = new Point($point);
                }

                if (!$point instanceof Point) {
                    throw new InvalidArgumentException(
                        sprintf("Invalid argument supplied: %", var_export($point, true))
                    );
                }

                $this->coordinates[] = $point;
            }
        } catch (TypeError $e) {
            throw new InvalidArgumentException(
                sprintf("Invalid position arguments supplied: %s", var_export($point, true))
            );
        }
    }
    
    public function getCoordinates(): array
    {
        return array_map(
            function (Point $point) {
                return $point->getCoordinates();
            },
            $this->coordinates
        );
    }

    public function getPositions(): array
    {
        return array_map(
            function (Point $point) {
                return $point->getPositions()[0];
            },
            $this->coordinates
        );
    }
}
