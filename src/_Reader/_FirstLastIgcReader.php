<?php

namespace Nemundo\Igc\Reader;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;

class FirstLastRawIgcReader extends AbstractRawIgcReader
{



    protected function getCoordinateFromItem($number) {


        $item = $this->inputList[$number];

        $coordinate = new GeoCoordinateAltitude();
        $coordinate->latitude = $item['lat'];
        $coordinate->longitude = $item['lon'];
        $coordinate->altitude = $item['alt'];

        return $coordinate;

    }


    public function getTakeoff() {



        $takeoff = $this->getCoordinateFromItem(0);
        return $takeoff;


    }


    public function getLanding() {




    }



    public function removeBetween() {




    }





}