<?php

namespace Nemundo\Igc\Optimizer;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Geo\Coordinate\GeoCoordinateDistance;
use Nemundo\Igc\Source\AbstractCoordinateSource;

class CoordinateReducer extends AbstractCoordinateSource  // AbstractBase
{


    /**
     * @var GeoCoordinateAltitude[]
     */
    public $coordinateList = [];

    /**
     * @var int
     */
    public $minHorizontalDistance = 100;

    /**
     * @var int
     */
    public $minVerticalDistance = 5;


    public function getGeoCoordinateList()
    {


        /** @var GeoCoordinateAltitude[] $list */
        $list = [];

        //$minHorizontalDistance = 100;
        //$minVerticalDistance = 5;

        $point1 = null;

        foreach ($this->source->getGeoCoordinateList() as $coordinate) {

            if ($coordinate->altitude !== 0) {

                if ($point1 !== null) {

                    $distance = new GeoCoordinateDistance();
                    $distance->geoCoordinateFrom = $point1;
                    $distance->geoCoordinateTo = $coordinate;

                    if (($distance->getDistanceInMetre() > $this->minHorizontalDistance) || ($distance->getAbsoluteVerticalDistance() > $this->minVerticalDistance)) {

                        $point1 = $coordinate;
                        $list[] = $point1;
                    }

                } else {

                    $point1 = $coordinate;
                    $list[] = $point1;

                }

            }

        }

        return $list;

    }

}