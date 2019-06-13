<?php

namespace Nemundo\Igc\Coordinate;

use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Type\DateTime\Time;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;


class IgcGeoCoordinateAltitude extends GeoCoordinateAltitude
{

    /**
     * @var string
     */
    public $time;

    /**
     * @var int
     */
    public $altitudeGps;

    /**
     * @var int
     */
    public $altitudeBarometer;

    /**
     * @var int
     */
    public $verticalDistance;

    /**
     * @var float
     */
    public $verticalSpeed;

    /**
     * @var int
     */
    public $horizontalDistance;


}