<?php

namespace Nemundo\Igc;

use Nemundo\Core\Base\DataSource\AbstractDataSource;
use Nemundo\Core\Date\DateTimeDifference;
use Nemundo\Core\Debug\Debug;
use Nemundo\Core\File\TextFileReader;
use Nemundo\Core\Log\LogMessage;
use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\DateTime\Time;
use Nemundo\Core\Type\File\File;
use Nemundo\Core\Type\Geo\GeoCoordinateAltitude;
use Nemundo\Core\Type\Text\Text;
use Nemundo\Igc\DegreeMinuteSecond\DegreeMinuteSecondCoordinate;

/**
 *
 * http://vali.fai-civl.org/documents/IGC-Spec_v1.00.pdf
 *
 */
class IgcReader extends AbstractDataSource
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


    public function __construct($filename)
    {

        $this->filename = $filename;
        $this->loadData();

    }


    protected function loadData()
    {

        //(new Debug())->write($this->filename);

        $this->loaded = true;
        $count = 0;

        $file = new File($this->filename);
        if ($file->exists()) {

            $textFile = new TextFileReader();
            $textFile->filename = $this->filename;

            $altitudePrevious = null;

            /** @var Time $timePrevious */
            $timePrevious = null;

            foreach ($textFile->getData() as $item) {

                $line = new Text($item);

                // Date
                // HFDTEDATE:070518,01
                if ($line->containsLeft('HFDTEDATE:')) {
                    $dateText = '20' . $line->getSubstring(14, 2) . '-' . $line->getSubstring(12, 2) . '-' . $line->getSubstring(10, 2);
                    $this->date = new Date($dateText);
                }

                if ($line->containsLeft('HFDTE')) {
                    if (is_null($this->date)) {
                        $dateText = '20' . $line->getSubstring(9, 2) . '-' . $line->getSubstring(7, 2) . '-' . $line->getSubstring(5, 2);
                        $this->date = new Date($dateText);
                    }
                }


                // Flight Track
                if ($item[0] == 'B') {

                    $hour = (int)$line->getSubstring(1, 2);
                    $minute = (int)$line->getSubstring(3, 2);
                    $second = (int)$line->getSubstring(5, 2);


                    if (!is_numeric($second) || !is_numeric($minute) || !is_numeric($second)) {
                        (new LogMessage())->writeError('Igc Reader No valid Number. Filename: ' . $this->filename);

                        (new Debug())->write($item);

                        (new Debug())->write($hour);
                        (new Debug())->write($minute);
                        (new Debug())->write($second);
                        exit;

                    }


                    $time = new Time($hour . ':' . $minute . ':' . $second);

                    $degreeCoordinate = new DegreeMinuteSecondCoordinate();

                    // Lat
                    $degreeCoordinate->lat->degree = $line->getSubstring(7, 2);
                    $degreeCoordinate->lat->minute = $line->getSubstring(9, 2) . '.' . $line->getSubstring(11, 3);
                    $degreeCoordinate->lat->direction = $line->getSubstring(14, 1);

                    // Lon
                    $degreeCoordinate->lon->degree = $line->getSubstring(15, 3);
                    $degreeCoordinate->lon->minute = $line->getSubstring(18, 2) . '.' . $line->getSubstring(20, 3);
                    $degreeCoordinate->lon->direction = $line->getSubstring(23, 1);

                    $coordinate = $degreeCoordinate->getGeoCoordinate();

                    // Altitude
                    $altitudeBarometer = $line->getSubstring(25, 5) * 1;
                    $altitudeGps = $line->getSubstring(30, 5) * 1;

                    if ($altitudeGps <> 0) {

                        if (($coordinate->latitude != 0) && ($coordinate->longitude != 0)) {

                            $verticalDistance = 0;
                            if ($altitudePrevious !== null) {
                                $verticalDistance = $altitudeGps - $altitudePrevious;
                            }

                            $second = 0;
                            if ($timePrevious !== null) {
                                $second = $time->getTimestamp() - $timePrevious->getTimestamp();
                            }

                            $verticalSpeed = 0;
                            if ($second !== 0) {
                                $verticalSpeed = $verticalDistance / $second;
                            }


                            /* if ($verticalSpeed < -10) {
                                 (new Debug())->write($verticalSpeed);
                             }*/

                            if (($verticalSpeed < 15) && ($verticalSpeed > -15)) {

                                $altitudePrevious = $altitudeGps;
                                $timePrevious = $time;


                                $geoCoordinate = new GeoCoordinateAltitude();
                                $geoCoordinate->latitude = $coordinate->latitude;
                                $geoCoordinate->longitude = $coordinate->longitude;
                                $geoCoordinate->altitude = $altitudeGps;

                                $igc = new IgcCoordinate();
                                $igc->time = $time;
                                $igc->geoCoordinate = $geoCoordinate;
                                $igc->altitudeGps = $altitudeGps;
                                $igc->altitudeBarometer = $altitudeBarometer;
                                $igc->verticalDistance = $verticalDistance;
                                $igc->verticalSpeed = $verticalSpeed;

                                $this->addItem($igc);


                                if ($count == 0) {
                                    $this->takeoffGeoCoordinate = $geoCoordinate;
                                    $this->takeoffTime = $time;
                                }
                                $count++;

                                $this->landingGeoCoordinate = $geoCoordinate;
                                $this->landingTime = $time;

                            }


                        }
                    }

                }

            }


            if ($this->date == null) {
                (new LogMessage())->writeError('Igc Reader Date is Null. Filename: ' . $this->filename);
            }


            $diff = new DateTimeDifference();
            $diff->dateFrom = $this->takeoffTime;
            $diff->dateUntil = $this->landingTime;

            // Problem mit
            //$this->airtimeMinute = $diff->getDifferenceInMinute();

        } else {
            (new LogMessage())->writeError('Igc File not found. Filename: ' . $this->filename);
        }


    }


    /**
     * @return IgcCoordinate[]
     */
    public function getData()
    {
        return parent::getData();
    }


}
