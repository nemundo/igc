<?php

namespace Nemundo\Igc\Reader;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Igc\Source\AbstractCoordinateSource;

abstract class AbstractIgcReader extends AbstractCoordinateSource  //  AbstractRawIgcReader
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