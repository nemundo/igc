<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Igc\Source\AbstractCoordinateSource;
use Nemundo\Igc\Source\AbstractSource;

class AltitudeIgcAnalyzer extends AbstractIgcAnalyzer
{


    public $alitutdeHigh=0;

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
            if ($coordinate->altitude > $this->alitutdeHigh) {
                $this->alitutdeHigh = $coordinate->altitude;
                $this->coordinateHigh = $coordinate;
            }
        }

        /*if ($coordinateHigh !== null) {
            $update = new FlightUpdate();
            $update->coordinateHigh = $coordinateHigh;
            $update->updateById($this->flightRow->id);
        }*/


    }


    public function getGeoCoordinateList()
    {
        // TODO: Implement getGeoCoordinateList() method.
    }

}