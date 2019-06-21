<?php

namespace Nemundo\Igc\Source;


use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;

abstract class AbstractSource extends AbstractBase
{

    /**
     * @return GeoCoordinateAltitude[]
     */
    abstract public function getGeoCoordinateList();

    abstract public function getGeoCoordinateCount();

    abstract public function getGeoCoordinateByNumer($number);


}