<?php

namespace Nemundo\Igc\Reader;


class KmlCoordinateIgcReader extends AbstractIgcReader
{


    public function getKmlCoordinate()
    {

        $content = '';

        $this->loadData();
        foreach ($this->list as $item) {
            $content .= $item['lon'] . ',' . $item['lat'] . ',' . $item['alt'] . PHP_EOL;
        }

        return $content;


    }


}