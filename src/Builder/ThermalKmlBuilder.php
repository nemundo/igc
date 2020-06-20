<?php


namespace Nemundo\Igc\Builder;


use Nemundo\Core\TextFile\Writer\TextFileWriter;
use Nemundo\Geo\Kml\Element\LineString;
use Nemundo\Igc\Analyzer\AbstractIgcAnalyzer;
use Nemundo\Igc\Optimizer\CoordinateReducer;


class ThermalKmlBuilder extends AbstractIgcAnalyzer
{


    public $minDistance = 0;

    // build
    public function buildKml($filename)
    {




        // Thermal
        $thermalMode = false;

        /** @var LineString $lineString */
        $lineString = null;

        $content = '';
        $altitudePrevious = null;

        $coordinatePrevious=null;


        foreach ($this->source->getGeoCoordinateList() as $coordinate) {

            $verticalDistance = 0;
            if ($altitudePrevious !== null) {
                $verticalDistance = $coordinate->altitude - $altitudePrevious;
            }

            if ($verticalDistance >$this->minDistance) {

                if (!$thermalMode) {

                    $lineString = new LineString();  //$container);
                    $lineString->addPoint($coordinatePrevious);
                    $thermalMode = true;

                }

                $lineString->addPoint($coordinate);


            } else {

                if ($thermalMode) {

                    $content .= $lineString->getContent() . PHP_EOL;
                    $thermalMode = false;

                }

            }

            $altitudePrevious = $coordinate->altitude;
            $coordinatePrevious = $coordinate;

        }

        /*
        $cacheFilename = new CacheFilename();
        $cacheFilename->type = 'thermal';

        $filename = $cacheFilename->getCacheFilename($this->flightRow);

        $text = new TextFileWriter($filename);
        $text->overwriteExistingFile = true;
        $text->addLine( $content);
        $text->saveFile();*/

        //return $content;




        /*$filename = (new KmlCacheFilename())->getCacheFilename($flightRow);

        (new FlightKmlCachePath())
            ->addPath($flightRow->date->getIsoDateFormat())
            ->createPath();*/


        //$igc = new IgcFile( $filename);

       /* $reducer = new CoordinateReducer($this->source);
        $reducer->minHorizontalDistance = 200;
        $reducer->minVerticalDistance = 20;*/

        //$cache=new FlightKmlCache($reducer);
        //$cache->import($flightRow);


        /*$content = '';
        //foreach ($this->source->getGeoCoordinateList() as $coordinate) {
        foreach ($reducer->getGeoCoordinateList() as $coordinate) {
            $content .= $coordinate->longitude . ',' . $coordinate->latitude . ',' . $coordinate->altitude . PHP_EOL;
        }*/

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