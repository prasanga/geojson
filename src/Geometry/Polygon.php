<?php

declare(strict_types=1);

namespace GeoJson\Geometry;

use InvalidArgumentException;

class Polygon extends Geometry
{
    public function __construct(array $coordinates)
    {
        try {
            if (empty($coordinates)) {
                throw new InvalidArgumentException("Please provide valid coordinate sets");
            }

            foreach ($coordinates as $lineStringCoordinates) {
                if ($lineStringCoordinates instanceof LineString) {
                    $lineStringCoordinates = $lineStringCoordinates->getCoordinates();
                }

                if (count($lineStringCoordinates) < 4) {
                    throw new InvalidArgumentException(
                        sprintf("%s LineStrings must have at least %d positions", $this->getType(), 4)
                    );
                }

                if ($lineStringCoordinates[0] !== array_reverse($lineStringCoordinates)[0]) {
                    throw new InvalidArgumentException(
                        sprintf("%s LineStrings must have equivalent start and end positions", $this->getType())
                    );
                }
                $this->coordinates[] = new LineString($lineStringCoordinates);
            }
        } catch (InvalidArgumentException $e) {
            throw $e;
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
