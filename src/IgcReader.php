<?php

namespace Nemundo\Igc;

use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Debug\Debug;
use Nemundo\Core\Log\LogMessage;
use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\DateTime\Time;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Core\Type\Text\Text;

/**
 *
 * http://vali.fai-civl.org/documents/IGC-Spec_v1.00.pdf
 *
 */
// IgcCoordinateReader

class IgcReader extends AbstractBase  // AbstractDataSource
{

    /**
     * @var Date
     */
    public $date;


    /**
     * @var Time
     */
    public $takeoffTime;

    /**
     * @var Time
     */
    public $landingTime;

    /**
     * @var int
     */
    public $airtimeMinute;

    /**
     * @var GeoCoordinateAltitude
     */
    public $takeoffGeoCoordinate;

    /**
     * @var GeoCoordinateAltitude
     */
    public $landingGeoCoordinate;

    /**
     * @var string
     */
    private $filename;

    //private $line;

    /**
     * @var GeoCoordinateAltitude[]
     */
    private $list = [];


    private $lineList = [];


    public function __construct($filename)
    {


        // Check File Exists


        $count = 0;

        //$textFile = new TextFileReader($this->filename);


        $content = '';

        $file = fopen($filename, 'r');
        while (($line = fgets($file)) !== false) {


            $this->lineList[] = $line;

            // Flight Track
            if ($line[0] == 'B') {

                /*$hour = (int)substr($line,1, 2);
                $minute = (int)substr($line,3, 2);
                $second = (int)substr($line,5, 2);

                $time = new Time($hour . ':' . $minute . ':' . $second);*/

                //$degreeCoordinate = new DegreeMinuteSecondCoordinate();

                $latDegree = substr($line, 7, 2);
                $latMinute = substr($line, 9, 2) . '.' . substr($line, 11, 3);
                $latDirection = substr($line, 14, 1);

                $lonDegree = substr($line, 15, 3);
                $lonMinute = substr($line, 18, 2) . '.' . substr($line, 20, 3);
                $lonDirection = substr($line, 23, 1);


                if (is_numeric($latDegree) && is_numeric($latMinute) && is_numeric($lonDegree) && is_numeric($lonMinute)) {


                    $lat = $latDegree + ($latMinute / 60);
                    if ($latDirection == 'S') {
                        $lat = $lat * -1;
                    }
                    $lat = round($lat, 5);


                    $lon = $lonDegree + ($lonMinute / 60);
                    if ($lonDirection == 'W') {
                        $lon = $lon * -1;
                    }
                    $lon = round($lon, 5);

                    $altitudeGps = substr($line, 30, 5) * 1;

                    $content .= $lon . ',' . $lat . ',' . $altitudeGps . PHP_EOL;


                    $coordinate = new GeoCoordinateAltitude();
                    $coordinate->latitude = $lat;
                    $coordinate->longitude = $lon;
                    $coordinate->altitude = $altitudeGps;
                    $this->list[] = $coordinate;

                } else {


                    /*
                    (new LogMessage())->writeError('IgcReader2. Invalid Number. Filename: ' . $filename);
                    (new Debug())->write($line);


                    (new Debug())->write($latDegree);
                    (new Debug())->write($latMinute);
                    (new Debug())->write($lonDegree);
                    (new Debug())->write($lonMinute);
                    (new Debug())->write();
                    (new Debug())->write();
                    (new Debug())->write();*/




                    }

            }

        }

        fclose($file);


        /*
        $filenameCoordinate = str_replace('.igc','.txt', $filename);

        $file = fopen($filenameCoordinate,'w');
        fwrite($file, $content);
        fclose($file);
*/


    }


    public function getDate()
    {

        $date = new Date();

        foreach ($this->lineList as $line) {

            //(new Debug())->write($line);

            $lineText = new Text($line);

            if ($lineText->containsLeft('HFDTEDATE:')) {
                $dateText = '20' . $lineText->getSubstring(14, 2) . '-' . $lineText->getSubstring(12, 2) . '-' . $lineText->getSubstring(10, 2);
                $this->date = new Date($dateText);
            }

            if ($lineText->containsLeft('HFDTE')) {
                //if ($date->isNull()) {
                $dateText = '20' . $lineText->getSubstring(9, 2) . '-' . $lineText->getSubstring(7, 2) . '-' . $lineText->getSubstring(5, 2);
                $date = new Date($dateText);
                //}
            }

        }

        return $date;

    }


    public function getGeoCoordinateList()
    {

        return $this->list;

    }


    // getKml
    public function getCoordinateContent()
    {


        $content = '';

        foreach ($this->lineList as $line) {

            // Flight Track
            if ($line[0] == 'B') {

                /*$hour = (int)substr($line,1, 2);
                $minute = (int)substr($line,3, 2);
                $second = (int)substr($line,5, 2);

                $time = new Time($hour . ':' . $minute . ':' . $second);*/

                //$degreeCoordinate = new DegreeMinuteSecondCoordinate();

                $latDegree = substr($line, 7, 2);
                $latMinute = substr($line, 9, 2) . '.' . substr($line, 11, 3);
                $latDirection = substr($line, 14, 1);

                $lonDegree = substr($line, 15, 3);
                $lonMinute = substr($line, 18, 2) . '.' . substr($line, 20, 3);
                $lonDirection = substr($line, 23, 1);

                $lat = $latDegree + ($latMinute / 60);
                if ($latDirection == 'S') {
                    $lat = $lat * -1;
                }
                $lat = round($lat, 5);


                $lon = $lonDegree + ($lonMinute / 60);
                if ($lonDirection == 'W') {
                    $lon = $lon * -1;
                }
                $lon = round($lon, 5);

                $altitudeGps = substr($line, 30, 5) * 1;

                $content .= $lon . ',' . $lat . ',' . $altitudeGps . PHP_EOL;


                /*
                $coordinate = new GeoCoordinateAltitude();
                $coordinate->latitude = $lat;
                $coordinate->longitude = $lon;
                $coordinate->altitude = $altitudeGps;
                $this->list[]= $coordinate;*/

            }

        }

        return $content;

    }


    /*
    private function getSubstring($start, $length)
    {

        //$substring=   substr($this->line, $start,$length);

        $max = $start + $length;

        $substring = '';
        for ($n = $start; $n < $max; $n++) {
            $substring = $substring. $this->line[$n];
        }

        return $substring;

    }*/


}
