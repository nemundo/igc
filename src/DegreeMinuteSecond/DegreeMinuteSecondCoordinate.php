<?php

namespace Nemundo\Igc\DegreeMinuteSecond;

use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Debug\Debug;
use Nemundo\Core\Log\LogMessage;
use Nemundo\Core\Type\Geo\GeoCoordinate;

class DegreeMinuteSecondCoordinate extends AbstractBase
{

    /**
     * @var DegreeMinuteSecond
     */
    public $lat;

    /**
     * @var DegreeMinuteSecond
     */
    public $lon;


    public function __construct()
    {
        $this->lat = new DegreeMinuteSecond();
        $this->lat->degree = 0;
        $this->lat->minute = 0;
        $this->lat->second = 0;

        $this->lon = new DegreeMinuteSecond();
        $this->lon->degree = 0;
        $this->lon->minute = 0;
        $this->lon->second = 0;

    }


    public function getGeoCoordinate()
    {

        $geoCoordinate = new GeoCoordinate();


        if (!is_numeric($this->lat->degree) || !is_numeric($this->lat->minute) || !is_numeric($this->lat->second)) {

            (new LogMessage())->writeError('DegreeMinuteSecondCoordinate No valid Number.');
            (new Debug())->write($this);

            $geoCoordinate->latitude = 0;
            $geoCoordinate->longitude = 0;

            return $geoCoordinate;

        }


        $lat = $this->lat->degree + ($this->lat->minute / 60) + ($this->lat->second / 3600);
        if ($this->lat->direction == 'S') {
            $lat = $lat * -1;
        }

        $lon = $this->lon->degree + ($this->lon->minute / 60) + ($this->lon->second / 3600);
        if ($this->lon->direction == 'W') {
            $lon = $lon * -1;
        }

        $geoCoordinate->latitude = $lat;
        $geoCoordinate->longitude = $lon;

        return $geoCoordinate;

    }

}
