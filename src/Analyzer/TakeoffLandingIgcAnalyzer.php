<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Core\Date\DateTimeDifference;

class TakeoffLandingIgcAnalyzer extends AbstractIgcAnalyzer
{


    public function getTakeoff() {

        $landing = $this->source->getGeoCoordinateByNumer(0);
        return $landing;

    }


    public function getLanding() {

        $landing = $this->source->getGeoCoordinateByNumer($this->source->getGeoCoordinateCount()-1);
        return $landing;

    }



    public function getTakeoffTime() {

       $time = $this->source->getTimeByNumber(0);
       return $time;

    }


    public function getLandingTime() {

        $time = $this->source->getTimeByNumber($this->source->getGeoCoordinateCount()-1);
        return $time;

    }


    public function getAirtimeInSeconds() {

        //$difference = new DateTimeDifference();

        $timeFirst  = strtotime($this->getLandingTime()->getIsoTime());  // '2011-05-12 18:20:20');
        $timeSecond = strtotime($this->getTakeoffTime()->getIsoTime());
        $differenceInSeconds =$timeFirst- $timeSecond;

        return $differenceInSeconds;


    }


}