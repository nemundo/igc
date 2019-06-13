<?php

namespace Nemundo\Igc\Reader;

use Nemundo\Core\Base\AbstractBase;
use Nemundo\Core\Log\LogMessage;
use Nemundo\Core\Type\DateTime\Date;
use Nemundo\Core\Type\Text\Text;

/**
 *
 * http://vali.fai-civl.org/documents/IGC-Spec_v1.00.pdf
 *
 */
// IgcCoordinateReader

abstract class AbstractIgcReader extends AbstractBase  // AbstractDataSource
{

    /**
     * @var Date
     */
    // public $date;


    /**
     * @var string
     */
    public $filename;


    public $pilot;


    //private $line;

    /**
     * @var string[]
     */
    protected $list = [];

    /**
     * @var bool
     */
    private $loaded = false;

    private $lineList = [];


    public function getFlightDate()
    {

        $this->loadData();

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


            //  HFPLTPILOT:Andreas NYFFENEGGER


            if ($lineText->containsLeft('HFPLTPILOT:')) {
                $this->pilot = $lineText->getSubstring(11);
            }

        }

        return $date;

    }


    protected function getValue($line, $key) {


        if (strpos($key, $line) === 0) {
            $found = true;
        }

        if ($lineText->containsLeft($key)) {
            $this->pilot = $lineText->getSubstring(11);
        }



    }


    protected function getList()
    {

        $this->loadData();
        return $this->list;

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

                        $this->list[] = $item;

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

            $this->loaded = true;

        }

    }


}
