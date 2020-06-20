<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Igc\Source\AbstractCoordinateSource;
use Nemundo\Igc\Source\AbstractSource;

class MaxAltitudeIgcAnalyzer extends AbstractIgcAnalyzer
{


    public $altitudeHigh=0;

    /**
     * @var GeoCoordinateAltitude
     */
    public $coordinateHigh;

    /**
     * @var GeoCoordinateAltitude
     */
    public $coordinateLow;


    public function __construct(AbstractSource $source)
    {

        parent::__construct($source);

        //$highestAlt = 0;
        $coordinateHigh = null;
        foreach ($this->source->getGeoCoordinateList() as $coordinate) {
            if ($coordinate->altitude > $this->altitudeHigh) {
                $this->altitudeHigh = $coordinate->altitude;
                $this->coordinateHigh = $coordinate;
            }
        }

        /*if ($coordinateHigh !== null) {
            $update = new FlightUpdate();
            $update->coordinateHigh = $coordinateHigh;
            $update->updateById($this->flightRow->id);
        }*/


    }

    public function getMaxAltitude() {
        return $this->coordinateHigh->altitude;
    }


    public function getMaxCoordinate() {

        return $this->coordinateHigh;

    }


    /*
    public function getGeoCoordinateList()
    {
        // TODO: Implement getGeoCoordinateList() method.
    }*/

}