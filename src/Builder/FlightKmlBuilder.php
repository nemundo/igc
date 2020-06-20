<?php


namespace Nemundo\Igc\Builder;


use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Debug\Debug;
use Nemundo\Core\TextFile\Writer\TextFileWriter;
use Nemundo\Igc\Analyzer\AbstractIgcAnalyzer;
use Nemundo\Igc\File\IgcFile;
use Nemundo\Igc\Optimizer\CoordinateReducer;
use Paranautik\Xcontest\Cache\FlightKmlCache;
use Paranautik\Xcontest\Cache\KmlCacheFilename;
use Paranautik\Xcontest\Crawler\Filename\FlightCacheFilename;
use Paranautik\Xcontest\Data\Flight\FlightRow;
use Paranautik\Xcontest\Path\FlightKmlCachePath;


class FlightKmlBuilder extends AbstractIgcAnalyzer
{



    // build
    public function buildKml($filename)
    {

        /*$filename = (new KmlCacheFilename())->getCacheFilename($flightRow);

        (new FlightKmlCachePath())
            ->addPath($flightRow->date->getIsoDateFormat())
            ->createPath();*/




        $content = '';
        foreach ($this->source->getGeoCoordinateList() as $coordinate) {
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