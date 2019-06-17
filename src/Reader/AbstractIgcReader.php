<?php

namespace Nemundo\Igc\Reader;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;

abstract class AbstractIgcReader extends AbstractRawIgcReader
{


    protected function getCoordinateFromItem($number) {

        $item = $this->inputList[$number];

        $coordinate = new GeoCoordinateAltitude();
        $coordinate->latitude = $item['lat'];
        $coordinate->longitude = $item['lon'];
        $coordinate->altitude = $item['alt'];

        return $coordinate;

    }


    protected function getCoordinateItemCount() {

        $count = sizeof($this->inputList);
        return $count;

    }



}