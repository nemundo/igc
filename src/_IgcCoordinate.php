<?php

namespace Nemundo\Igc;

use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Type\DateTime\Time;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;


class IgcCoordinate extends AbstractBase
{

    /**
     * @var Time
     */
    public $time;

    /**
     * @var GeoCoordinateAltitude
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