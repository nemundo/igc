<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Geo\Coordinate\GeoCoordinateDistance;

class TrackLengthAnalyzer extends AbstractIgcAnalyzer
{


    /**
     * @var GeoCoordinateAltitude[]
     */
    //public $coordinateList;


public function getTrackLength()
    {

        $distance = 0;
        $previousCoordinate = null;

        foreach ($this->igcReader->getGeoCoordinateList() as $coordinate) {

            $geoDistance = new GeoCoordinateDistance();
            $geoDistance->geoCoordinateFrom = $previousCoordinate;
            $geoDistance->geoCoordinateTo = $coordinate;
            $distance = $distance + $geoDistance->getDistance();

            $previousCoordinate = $coordinate;

        }

        return $distance;

    }


}