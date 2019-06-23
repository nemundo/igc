<?php

namespace Nemundo\Igc\Source;


abstract class AbstractCoordinateSource extends AbstractSource
{

    use LoadSourceTrait;



    public function getGeoCoordinateCount()
    {
        // TODO: Implement getGeoCoordinateCount() method.
    }


    public function getGeoCoordinateByNumer($number)
    {
        // TODO: Implement getGeoCoordinateByNumer() method.
    }






    /**
     * @var AbstractSource
     */
   /* protected $source;

    public function __construct(AbstractSource $source)
    {
        $this->source = $source;
    }*/

}