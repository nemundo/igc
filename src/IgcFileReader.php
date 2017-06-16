<?php

namespace Nemundo\Igc;


use Nemundo\Core\Base\DataSource\AbstractDataSource;
use Nemundo\Core\File\TextFileReader;
use Nemundo\Core\Type\Date;
use Nemundo\Core\Type\Text;
use Nemundo\Core\Type\Time;
use Nemundo\Geo\Coordinate\DegreeMinuteSecond\DegreeMinuteSecondCoordinate;


/**
 *
 * http://carrier.csi.cam.ac.uk/forsterlewis/soaring/igc_file_format/
 *
 */
class IgcFileReader extends AbstractDataSource
{

    /**
     * @var string
     */
    public $filename;

    /**
     * @var Date
     */
    private $date;


    public function __construct()
    {
        $this->date = new Date();
    }


    protected function loadData()
    {

        $this->loaded = true;

        if (!$this->checkProperty('filename')) {
            return;
        }

        $textFile = new TextFileReader();
        $textFile->filename = $this->filename;

        foreach ($textFile->getData() as $item) {

            $line = new Text($item);

            // Date
            if ($line->containsLeft('HFDTE')) {
                $dateText = '20' . $line->substring(9, 2) . '-' . $line->substring(7, 2) . '-' . $line->substring(5, 2);
                $this->date = new Date($dateText);
            }

            // Flight Track
            if ($item[0] == 'B') {

                $hour = $line->substring(1, 2);
                $minute = $line->substring(3, 2);
                $second = $line->substring(5, 2);

                $time = new Time($hour . ':' . $minute . ':' . $second);

                $coordinate = new DegreeMinuteSecondCoordinate();

                // Lat
                $coordinate->lat->degree = $line->substring(7, 2);
                $coordinate->lat->minute = $line->substring(9, 2) . '.' . $line->substring(11, 3);
                $coordinate->lat->direction = $line->substring(14, 1);

                // Lon
                $coordinate->lon->degree = $line->substring(15, 3);
                $coordinate->lon->minute = $line->substring(18, 2) . '.' . $line->substring(20, 3);
                $coordinate->lon->direction = $line->substring(23, 1);

                // Altitude
                $altitudeBarometer = $line->substring(25, 5) * 1;
                $altitudeGps = $line->substring(30, 5) * 1;


                $igc = new IgcCoordinate();
                $igc->time = $time;
                $igc->geoCoordinate = $coordinate->getGeoCoordinate();
                $igc->altitudeGps = $altitudeGps;
                $igc->altitudeBarometer = $altitudeBarometer;

                $this->addItem($igc);

            }
        }

    }


    public function getFlightDate()
    {
        $this->loadData();
        return $this->date;
    }


    /**
     * @return IgcCoordinate[]
     */
    public function getData()
    {
        return parent::getData();
    }


}