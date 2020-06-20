<?php


namespace Nemundo\Igc\Builder;


use Nemundo\Core\TextFile\Writer\TextFileWriter;
use Nemundo\Igc\Analyzer\AbstractIgcAnalyzer;
use Nemundo\Igc\Optimizer\CoordinateReducer;


class FlightKmlBuilder extends AbstractIgcAnalyzer
{


    // build
    public function buildKml($filename)
    {

        /*$filename = (new KmlCacheFilename())->getCacheFilename($flightRow);

        (new FlightKmlCachePath())
            ->addPath($flightRow->date->getIsoDateFormat())
            ->createPath();*/


        //$igc = new IgcFile( $filename);

        $reducer = new CoordinateReducer($this->source);
        $reducer->minHorizontalDistance = 200;
        $reducer->minVerticalDistance = 20;

        //$cache=new FlightKmlCache($reducer);
        //$cache->import($flightRow);


        $content = '';
        //foreach ($this->source->getGeoCoordinateList() as $coordinate) {
        foreach ($reducer->getGeoCoordinateList() as $coordinate) {
            $content .= $coordinate->longitude . ',' . $coordinate->latitude . ',' . $coordinate->altitude . PHP_EOL;
        }

        $text = new TextFileWriter($filename);
        $text->overwriteExistingFile = true;
        $text->addLine($content);
        $text->saveFile();

    }


    //public $overrideCache=false;

    // buildKml
    /*public function buildKml(FlightRow $flightRow) {

        $cacheFilename = new FlightCacheFilename($flightRow);
        $igcFilename = $cacheFilename->getIgcFilename();

        if ($cacheFilename->existsFile()) {

            $igc = new IgcFile( $igcFilename);

            $reducer = new CoordinateReducer($igc);
            $reducer->minHorizontalDistance=200;
            $reducer->minVerticalDistance=20;

            $cache=new FlightKmlCache($reducer);
            $cache->import($flightRow);

        } else {
            (new Debug())->write('Igc does not exist');
        }

    }*/


}