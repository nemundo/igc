<?php

namespace Nemundo\Igc\Reader;


class KmlCoordinateRawIgcReader extends AbstractRawIgcReader
{


    public function getKmlCoordinate()
    {

        $content = '';

        $this->loadData();
        foreach ($this->inputList as $item) {
            $content .= $item['lon'] . ',' . $item['lat'] . ',' . $item['alt'] . PHP_EOL;
        }

        return $content;


    }


}