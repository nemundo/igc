<?php

require __DIR__.'/../../../config.php';

$performance = new \Nemundo\App\Performance\PerformanceStopwatch('Igc');

$filename = __DIR__.'/igc/190331_NKN_01.igc';



$reader = new \Nemundo\Igc\IgcReader($filename);


//$reader->getGeoCoordinateList();

//$reader->getCoordinateContent();



//(new \Nemundo\Core\Debug\Debug())->write($reader->getCoordinateContent());



//(new \Nemundo\Core\Debug\Debug())->write('Date: '.$reader->date->getIsoDateFormat());
/*foreach ($reader->getGeoCoordinateList() as $igcCoordinate) {
    (new \Nemundo\Core\Debug\Debug())->write($igcCoordinate);
}*/

