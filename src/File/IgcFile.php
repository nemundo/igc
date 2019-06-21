<?php

namespace Nemundo\Igc\File;


use Nemundo\Core\Debug\Debug;
use Nemundo\Core\Log\LogMessage;
use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Core\Type\Text\Text;
use Nemundo\Igc\Source\AbstractSource;

class IgcFile extends AbstractSource
{


    /**
     * @var string
     */
    private $filename;


    /**
     * @var Date
     */
    public $date;

    /**
     * @var string
     */
    public $pilot;

    /**
     * @var string[][]
     */
    protected $inputList = [];

    /**
     * @var string[]
     */
    protected $outputList = [];

    /**
     * @var bool
     */
    private $loaded = false;

    private $lineList = [];

    private $propertyLineList = [];


    public function __construct($filename, $loadProperty = true)
    {

        $this->filename = $filename;
        $this->loadData();
        /*if ($loadProperty) {
         $this->loadProperty();
        }*/
    }


    public function getFlightDate()
    {

    }


    public function getPilot()
    {


    }


    public function getGeoCoordinateList()
    {

        // tmp List


        /** @var GeoCoordinateAltitude[] $list */
        $list = [];


        foreach ($this->getInputList() as $item) {

            $coordinate = new GeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];

            $list[] = $coordinate;

        }

        return $list;

    }


    public function getGeoCoordinateCount()
    {
        $count = sizeof($this->inputList);
        return $count;

        // TODO: Implement getGeoCoordinateCount() method.
    }


    public function getGeoCoordinateByNumer($number)
    {

        $coordinate = null;
        if (isset($this->inputList[$number])) {
            $item = $this->inputList[$number];

            $coordinate = new GeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];

        } else {

            (new Debug())->write('No Coordinate. Filename: ' . $this->filename);

        }


        return $coordinate;

    }


//    abstract public function getGeoCoordinateCount();

//    abstract public function getGeoCoordinateByNumer($number);


    protected function loadProperty()
    {


        // reading until first B Record

        $this->date = new Date();

        foreach ($this->propertyLineList as $line) {


            $dateKey = 'HFDTEDATE:';

            $value = $this->getValue($line, $dateKey);
            if ($value !== null) {

                //(new Debug())->write('Datum '.$value);

                $lineText = new Text($line);
                $dateText = '20' . $lineText->getSubstring(14, 2) . '-' . $lineText->getSubstring(12, 2) . '-' . $lineText->getSubstring(10, 2);

                //(new Debug())->write('Datum '.$dateText);


                $this->date = new Date($dateText);


                //(new Debug())->write('Datum2: '.$this->date->getIsoDateFormat());


            }


            /*
             *
             * HFDTE140318*/


            $value = $this->getValue($line, 'HFDTE');
            if ($value !== null) {
                //(new Debug())->write('CHF');

                if (!$this->hasValue($line, $dateKey)) {

                    //if ( //$value = $this->getValue($line, 'HFDTEDATE:');

                    $lineText = new Text($line);
                    $dateText = '20' . $lineText->getSubstring(9, 2) . '-' . $lineText->getSubstring(7, 2) . '-' . $lineText->getSubstring(5, 2);
                    $this->date = new Date($dateText);
                }

            }


            $value = $this->getValue($line, 'HFPLTPILOT:');
            if ($value !== null) {
                $this->pilot = $value;
            }

            $value = $this->getValue($line, 'HOPLTPILOT:');
            if ($value !== null) {
                $this->pilot = $value;
            }


        }


        if ($this->date->isNull()) {
            (new LogMessage())->writeError('No valid Date. Filename: ' . $this->filename);
        }


    }


    protected function hasValue($line, $key)
    {

        $value = false;
        if (strpos($line, $key) === 0) {
            $value = true;
        }

        return $value;

    }


    protected function getValue($line, $key)
    {

        $value = null;
        //if (strpos($line, $key) === 0) {
        if ($this->hasValue($line, $key)) {
            $value = substr($line, strlen($key));
        }

        return $value;

    }


    protected function getInputList()
    {

        $this->loadData();
        return $this->inputList;

    }


    protected function loadData()
    {

        //if (!$this->loaded) {

        $file = fopen($this->filename, 'r');
        while (($line = fgets($file)) !== false) {


            $this->lineList[] = $line;


            if ($line[0] == 'B') {


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

                    $hour = (int)substr($line, 1, 2);
                    $minute = (int)substr($line, 3, 2);
                    $second = (int)substr($line, 5, 2);


                    $item = [];
                    $item['lat'] = $lat;
                    $item['lon'] = $lon;
                    $item['alt'] = $altitudeGps;
                    $item['time'] = $hour . ':' . $minute . ':' . $second;

                    $this->inputList[] = $item;

                } else {

                    (new LogMessage())->writeError('IgcReader2. Invalid Number. Filename: ' . $this->filename);

                }

            }

            /*
                            $performance = new PerformanceStopwatch('CoordRead');

                            $latDegree = substr($line, 7, 2);
                            $latMinute = substr($line, 9, 2) . '.' . substr($line, 11, 3);
                            $latDirection = substr($line, 14, 1);

                            $lonDegree = substr($line, 15, 3);
                            $lonMinute = substr($line, 18, 2) . '.' . substr($line, 20, 3);
                            $lonDirection = substr($line, 23, 1);

                            $performance->stopStopwatch();

                            $performance = new PerformanceStopwatch('CoordCalc');

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

                                $hour = (int)substr($line, 1, 2);
                                $minute = (int)substr($line, 3, 2);
                                $second = (int)substr($line, 5, 2);

                                $item = [];
                                $item['lat'] = $lat;
                                $item['lon'] = $lon;
                                $item['alt'] = $altitudeGps;
                                $item['time'] = $hour . ':' . $minute . ':' . $second;

                                $this->inputList[] = $item;

                                $performance->stopStopwatch();

                            } else {

                                (new LogMessage())->writeError('IgcReader2. Invalid Number. Filename: ' . $this->filename);

                            }

                            $performance->stopStopwatch();

                        } else {

                            $this->propertyLineList[] = $line;

                        }*/

        }

        fclose($file);

        //$this->loadProperty();

        //$this->loaded = true;

        //}

    }


}