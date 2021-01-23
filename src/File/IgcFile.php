<?php

namespace Nemundo\Igc\File;


use Nemundo\Core\Debug\Debug;
use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\DateTime\Time;
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
     * @var bool
     */
    private $valid = true;

    /**
     * @var Date
     */
    public $date;

    /**
     * @var string
     */
    public $pilot;

    /**
     * @var string
     */
    public $glider;

    /**
     * @var string[][]
     */
    public $inputList = [];

    /**
     * @var GeoCoordinateAltitude[]
     */
    protected $geoCoordinateList;

    /**
     * @var string[]
     */
    //protected $outputList = [];

    /**
     * @var bool
     */
    //private $loaded = false;

    //private $lineList = [];

    /**
     * @var string[]
     */
    private $propertyLineList = [];


    public function __construct($filename)
    {

        $this->filename = $filename;
        $this->loadData();
        $this->loadProperty();

    }


    public function isValid()
    {
        return $this->valid;
    }


    public function getGeoCoordinateList()
    {

        if ($this->geoCoordinateList == null) {

            $this->geoCoordinateList = [];

            foreach ($this->inputList as $item) {

                $coordinate = new GeoCoordinateAltitude();
                $coordinate->latitude = $item['lat'];
                $coordinate->longitude = $item['lon'];
                $coordinate->altitude = $item['alt'];

                $this->geoCoordinateList[] = $coordinate;

            }

        }

        return $this->geoCoordinateList;

    }


    public function getGeoCoordinateCount()
    {

        $count = sizeof($this->inputList);
        return $count;

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


    public function getTimeByNumber($number)
    {

        $item = $this->inputList[$number];
        $time = new Time($item['time']);

        return $time;

    }


    protected function loadProperty()
    {

        $this->date = new Date();

        foreach ($this->propertyLineList as $line) {

            $dateKey = 'HFDTEDATE:';

            $value = $this->getValue($line, $dateKey);
            if ($value !== null) {

                $lineText = new Text($line);
                $dateText = '20' . $lineText->getSubstring(14, 2) . '-' . $lineText->getSubstring(12, 2) . '-' . $lineText->getSubstring(10, 2);
                $this->date = new Date($dateText);

            }

            $value = $this->getValue($line, 'HFDTE');
            if ($value !== null) {

                if (!$this->hasValue($line, $dateKey)) {
                    $lineText = new Text($line);
                    $dateText = '20' . $lineText->getSubstring(9, 2) . '-' . $lineText->getSubstring(7, 2) . '-' . $lineText->getSubstring(5, 2);
                    $this->date = new Date($dateText);
                }

            }

            $value = $this->getValue($line, 'HFPLTPILOT:');
            if ($value !== null) {
                $this->pilot = (new Text($value))->utf8Encode()->getValue();
            }

            $value = $this->getValue($line, 'HOPLTPILOT:');
            if ($value !== null) {
                $this->pilot = (new Text($value))->utf8Encode()->getValue();
            }


            $value = $this->getValue($line, 'HPGTYGLIDERTYPE:');
            if ($value !== null) {
                $this->glider = (new Text($value))->utf8Encode()->getValue();
            }

        }

        if ($this->date->isNull()) {
            $this->valid = false;
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
        if ($this->hasValue($line, $key)) {
            $value = substr($line, strlen($key));
        }

        return $value;

    }


    protected function loadData()
    {

        $file = fopen($this->filename, 'r');
        while (($line = fgets($file)) !== false) {

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

                    $altitudeGpsText = substr($line, 30, 5);
                    $altitudeGpsText = ltrim($altitudeGpsText, '0');
                    $altitudeGps = (int)$altitudeGpsText;
                    if (!is_int($altitudeGps)) {
                        (new Debug())->write('No valid Altitude. Value: ' . $altitudeGps . ' Filename: ' . $this->filename);
                    }
                    //$altitudeGps = (int)$altitudeGpsText;
                    //$altitudeGps = $altitudeGps*1;  //   substr($line, 30, 5) * 1;

                    $hour = (int)substr($line, 1, 2);
                    $minute = (int)substr($line, 3, 2);
                    $second = (int)substr($line, 5, 2);

                    $item = [];
                    $item['lat'] = $lat;
                    $item['lon'] = $lon;
                    $item['alt'] = $altitudeGps;
                    $item['time'] = $hour . ':' . $minute . ':' . $second;

                    $this->inputList[] = $item;

                }
                /*else {

                    //(new LogMessage())->writeError('IgcReader2. Invalid Number. Filename: ' . $this->filename);

                }*/

            } else {

                $this->propertyLineList[] = $line;

            }

        }

        fclose($file);

    }

}