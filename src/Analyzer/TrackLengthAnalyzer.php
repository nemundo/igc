<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Geo\Coordinate\GeoCoordinateDistance;

class TrackLengthAnalyzer extends AbstractIgcAnalyzer
{


    public function getTrackLength()
    {

        $distance = 0;
        $previousCoordinate = null;

        foreach ($this->source->getGeoCoordinateList() as $coordinate) {

            if ($previousCoordinate !== null) {
                $geoDistance = new GeoCoordinateDistance();
                $geoDistance->geoCoordinateFrom = $previousCoordinate;
                $geoDistance->geoCoordinateTo = $coordinate;
                $distance = $distance + $geoDistance->getDistance();
            }

            $previousCoordinate = $coordinate;

        }

        return $distance;

    }


}