<?php

namespace Nemundo\Igc\Kml;


use Nemundo\Core\TextFile\Writer\TextFileWriter;
use Nemundo\Geo\Kml\Element\LineString;
use Nemundo\Geo\Kml\Object\KmlLine;
use Nemundo\Html\Container\AbstractContainer;
use Nemundo\Igc\Analyzer\AbstractIgcAnalyzer;
use Nemundo\Igc\Source\AbstractSource;
use Paranautik\App\Flight\Cache\CacheFilename;

class ThermalKml extends AbstractIgcAnalyzer  // AbstractSource  // AbstractIgcAnalyzer
{


    public $minDistance = 0;

    public function getKml(AbstractContainer $container=null)
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

                    $lineString = new LineString($container);
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

        return $content;

        //return $lineString;

    }

}