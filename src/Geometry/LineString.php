<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use InvalidArgumentException;
use TypeError;

class LineString extends Geometry
{
    public function __construct(array $coordinates)
    {
        if (count($coordinates) < 2) {
            throw new InvalidArgumentException(sprintf("%s expects 2 or more sets of positions", $this->getType()));
        }
        
        try {
            foreach ($coordinates as $position) {
                if ($position instanceof Position) {
                    $position = $position->toArray();
                }

                if (!is_array($position)) {
                    throw new InvalidArgumentException(
                        sprintf("Invalid position argument supplied: %s", var_export($position, true))
                    );
                }
                
                if (count($position) < 2) {
                    throw new InvalidArgumentException(
                        sprintf(
                            "Invalid position value. A position must have at least %d values",
                            2
                        )
                    );
                }
                $this->coordinates[] = new Position($position[0], $position[1], $position[2] ?? null);
            }
        } catch (TypeError $e) {
            throw new InvalidArgumentException(
                sprintf("Invalid position arguments supplied: %s", var_export($position, true))
            );
        }
    }

    public function getCoordinates(): array
    {
        return array_map(
            function (Position $position) {
                return $position->toArray();
            },
            $this->coordinates
        );
    }

    public function getPositions(): array
    {
        return $this->coordinates;
    }
}
