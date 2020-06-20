<?php

namespace Nemundo\Igc\Kml;


use Nemundo\Core\TextFile\Writer\TextFileWriter;
use Nemundo\Geo\Kml\Element\LineString;
use Nemundo\Igc\Analyzer\AbstractIgcAnalyzer;
use Paranautik\App\Flight\Cache\CacheFilename;

class FlightKml extends AbstractIgcAnalyzer
{

    public function getKml()
    {

        $lineString = new LineString();

        $content = '';
        foreach ($this->source->getGeoCoordinateList() as $coordinate) {
        //    $content .= $coordinate->longitude . ',' . $coordinate->latitude . ',' . $coordinate->altitude . PHP_EOL;
            $lineString->addPoint($coordinate);
        }



        return $lineString;

    }

}