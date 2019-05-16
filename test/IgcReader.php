<?php

require __DIR__.'/../../../config.php';

$performance = new \Nemundo\App\Performance\PerformanceStopwatch('Igc');

$filename = __DIR__.'/190331_NKN_01.igc';
$reader = new \Nemundo\Igc\IgcReader($filename);
//(new \Nemundo\Core\Debug\Debug())->write('Date: '.$reader->date->getIsoDateFormat());
//foreach ($reader->getData() as $igcCoordinate) {
    //(new \Nemundo\Core\Debug\Debug())->write($igcCoordinate);
//}

$performance->stopStopwatch();
$performance->writeToScreen();


