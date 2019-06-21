<?php

namespace Nemundo\Igc\Analyzer;


class LandingIgcAnalyzer extends AbstractIgcAnalyzer
{


    /*
    public function getLandingOld() {


        $landing = null;

        //$landing = $this->source->getGeoCoordinateByNumer($this->source->getGeoCoordinateCount()-1);

        foreach ($this->source->getGeoCoordinateList() as $coordinate) {
            $landing = $coordinate;
        }

        return $landing;


    }*/



    public function getLanding() {


        $landing = null;

        $landing = $this->source->getGeoCoordinateByNumer($this->source->getGeoCoordinateCount()-1);

        /*foreach ($this->source->getGeoCoordinateList() as $coordinate) {
            $landing = $coordinate;
        }*/

        return $landing;


    }

}