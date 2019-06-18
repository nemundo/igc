<?php

namespace Nemundo\Igc\Reader;


use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Igc\Coordinate\IgcGeoCoordinateAltitude;

class IgcReader extends AbstractIgcReader
{

    public $filename;



/*
    public function getTakeoff() {


        $this->loadData();

        $takeoff = $this->getCoordinateFromItem(0);
        return $takeoff;


    }



    public function getLanding() {


        $this->loadData();

        $takeoff = $this->getCoordinateFromItem($this->getCoordinateItemCount()-1);
        return $takeoff;


    }*/







    public function getGeoCoordinateList()
    {

        // tmp List




        /** @var GeoCoordinateAltitude[] $list */
        $list = [];


        $reader = new RawIgcReader();
        $reader->filename=$this->filename;

//        foreach ($this->getInputList() as $item) {
            foreach ($reader->getInputList() as $item) {

            $coordinate = new GeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];

            $list[] = $coordinate;

        }

        return $list;

    }


    public function getIgcGeoCoordinateList()
    {


        $altitudePrevious = null;

        /** @var \DateTime $timePrevious */
        $timePrevious = null;


        /** @var IgcGeoCoordinateAltitude[] $list */
        $list = [];

        foreach ($this->getInputList() as $item) {

            $coordinate = new IgcGeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];
            $coordinate->time = $item['time'];


            $time = new \DateTime($item['time']);

            $coordinate->verticalDistance = 0;
            if ($altitudePrevious !== null) {
                $coordinate->verticalDistance = $coordinate->altitude - $altitudePrevious;
            }

            $second = 0;
            if ($timePrevious !== null) {
                $second = $time->getTimestamp() - $timePrevious->getTimestamp();
            }

            $coordinate->verticalSpeed = 0;
            if ($second !== 0) {
                $coordinate->verticalSpeed = $coordinate->verticalDistance / $second;
            }


            $altitudePrevious = $coordinate->altitude;
            $timePrevious = $time;


            $list[] = $coordinate;

        }

        return $list;

    }


    /*
    public function getCleanedGeoCoordinateList()
    {

        /** @var GeoCoordinateAltitude[] $list */
    /*    $list = [];

        $hideCoordinate = false;
        $hideCoordinateCount=null;

        foreach ($this->getIgcGeoCoordinateList() as $igcCoordinate) {


            if (($igcCoordinate->verticalSpeed > 10) || ($igcCoordinate->verticalSpeed < -10)) {
                //$list[] = $igcCoordinate;
                $hideCoordinate = true;
                $hideCoordinateCount = 0;
            }



            if (!$hideCoordinate) {
                $list[] = $igcCoordinate;
            } else {

                $hideCoordinateCount++;

                if ($hideCoordinateCount > 10) {
                    $hideCoordinate=false;
                }

            }


        }

        return $list;

    }


    public function getOptimizedGeoCoordinateList()
    {


        /** @var GeoCoordinateAltitude[] $list */
     /*   $list = [];

        foreach ($this->getInputList() as $item) {

            $coordinate = new GeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];

            $list[] = $coordinate;

        }

        return $list;

    }


    public function getReducedGeoCoordinateList()
    {

        /** @var GeoCoordinateAltitude[] $list */
     /*   $list = [];

        foreach ($this->getInputList() as $item) {

            $coordinate = new GeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];

            $list[] = $coordinate;

        }

        return $list;

    }



    public function getKmlCoordinate()
    {


        $content = '';

        foreach ($this->getInputList() as $item) {
            $content .= $item['lon'] . ',' . $item['lat'] . ',' . $item['alt'] . PHP_EOL;
        }

        return $content;

    }*/

}