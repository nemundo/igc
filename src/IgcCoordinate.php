<?php

namespace Nemundo\Igc;


use Nemundo\Core\Type\GeoCoordinate;
use Nemundo\Core\Type\Time;


class IgcCoordinate
{

    /**
     * @var Time
     */
    public $time;

    /**
     * @var GeoCoordinate
     */
    public $geoCoordinate;

    /**
     * @var int
     */
    public $altitudeGps;

    /**
     * @var int
     */
    public $altitudeBarometer;


    //public $speedVertical;

    //public $speedHorizontal;


}