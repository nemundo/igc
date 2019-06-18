<?php

namespace Paranautik\App\Flight\Analyzer\Import;


use Paranautik\App\Flight\Data\Flight\FlightRow;
use Paranautik\App\Flight\Data\Flight\FlightUpdate;
use Paranautik\App\Flight\Data\FlightCoordinate\FlightCoordinateReader;

class MaxAltitudeGainFlightAnalyzer extends AbstractAnalyzerImport
{

    public function import()
    {


        $minAltitude = 9999;
        $maxAltitude = -9999;
        $maxAltitudeGain = 0;


        $reader = new FlightCoordinateReader();
        $reader->filter->andEqual($reader->model->flightId, $this->flightRow->id);
        $reader->addOrder($reader->model->id);
        foreach ($reader->getData() as $coordinateRow) {


            if ($coordinateRow->coordinate->altitude < $minAltitude) {
                $minAltitude = $coordinateRow->coordinate->altitude;
            }

            if ($coordinateRow->coordinate->altitude > $maxAltitude) {
                $maxAltitude = $coordinateRow->coordinate->altitude;

                $altitudeGain = $maxAltitude - $minAltitude;

                if ($altitudeGain > $maxAltitudeGain) {
                    $maxAltitudeGain = $altitudeGain;
                }

            }

        }

        $update = new FlightUpdate();
        $update->maxAltitudeGain = $maxAltitudeGain;
        $update->updateById($this->flightRow->id);

    }

}