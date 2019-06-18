<?php

namespace Nemundo\Igc\File;


use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Core\Type\Text\Text;
use Nemundo\Igc\Source\AbstractCoordinateSource;
use Nemundo\Igc\Source\AbstractSource;

class IgcFile extends AbstractSource  // AbstractCoordinateSource
{


    private $filename;


    /**
     * @var Date
     */
     public $date;


    /**
     * @var string
     */
    //public $filename;


    public $pilot;


    //private $line;

    /**
     * @var string[]
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



    public function __construct($filename)
    {

        $this->filename = $filename;
        $this->loadData();

    }







    public function getGeoCoordinateList()
    {

        // tmp List



        /** @var GeoCoordinateAltitude[] $list */
        $list = [];


        //$reader = new RawIgcReader();
        //$reader->filename=$this->filename;

        foreach ($this->getInputList() as $item) {
//        foreach ($reader->getInputList() as $item) {

            $coordinate = new GeoCoordinateAltitude();
            $coordinate->latitude = $item['lat'];
            $coordinate->longitude = $item['lon'];
            $coordinate->altitude = $item['alt'];

            $list[] = $coordinate;

        }

        return $list;

    }





    protected function loadProperty()
    {

        //$this->loadData();

        $this->date = new Date();

        foreach ($this->lineList as $line) {

            $value =$this->getValue($line,'HFDTEDATE:');
            if ($value!==null) {
                $lineText = new Text($line);
                $dateText = '20' . $lineText->getSubstring(14, 2) . '-' . $lineText->getSubstring(12, 2) . '-' . $lineText->getSubstring(10, 2);

                $this->date = new Date($dateText);
            }

            $value =$this->getValue($line,'HFDTE');
            if ($value!==null) {
                $lineText = new Text($line);
                $dateText = '20' . $lineText->getSubstring(9, 2) . '-' . $lineText->getSubstring(7, 2) . '-' . $lineText->getSubstring(5, 2);
                $this->date = new Date($dateText);
            }

            $value = $this->getValue($line, 'HFPLTPILOT:');
            if ($value !== null) {
                $this->pilot = $value;
            }

        }

        //return $date;

    }


    protected function getValue($line, $key)
    {

        $value = null;
        if (strpos($line,$key) === 0) {
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

        if (!$this->loaded) {

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


                        (new LogMessage())->writeError('IgcReader2. Invalid Number. Filename: ' . $filename);


                        /*(new Debug())->write($line);


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

            $this->loadProperty();

            $this->loaded = true;

        }

    }





}