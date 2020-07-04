<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use InvalidArgumentException;
use TypeError;

class MultiLineString extends Geometry
{
    public function __construct(array $coordinates)
    {
        if (empty($coordinates)) {
            throw new InvalidArgumentException(
                sprintf("%s requires at least one set of LineString coordinates", $this->getType())
            );
        }
        foreach ($coordinates as $lineString) {
            if (is_array($lineString)) {
                $lineString = new LineString($lineString);
            }

            if (!$lineString instanceof LineString) {
                throw new InvalidArgumentException(
                    sprintf("Invalid argument supplied: %", var_export($lineString, true))
                );
            }
            
            $this->coordinates[] = $lineString;
        }
    }

    public function getCoordinates(): array
    {
        return array_map(
            function (LineString $lineString) {
                return $lineString->getCoordinates();
            },
            $this->coordinates
        );
    }
    
    public function getPositions(): array
    {
        return array_map(
            function (LineString $lineString) {
                return $lineString->getPositions();
            },
            $this->coordinates
        );
    }
}
