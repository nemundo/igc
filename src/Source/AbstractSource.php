<?php

namespace Nemundo\Igc\Source;


use Nemundo\Core\Base\AbstractBase;

abstract class AbstractSource extends AbstractBase
{

    abstract public function getGeoCoordinateList();

}