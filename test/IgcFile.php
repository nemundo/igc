<?php

require __DIR__ . '/../../../config.php';

//$performance = new \Nemundo\App\Performance\PerformanceStopwatch('Igc');

$filename = __DIR__ . '/igc/190331_NKN_01.igc';
//$filename = 'C:\test\track.igc';
$filename = 'C:\Users\Urs\Downloads\2021-01-19_12.38_Fanas_Sassauna.igc';


$file = new \Nemundo\Igc\File\IgcFile($filename);

if ($file->isValid()) {

    (new \Nemundo\Core\Debug\Debug())->write('Pilot: ' . $file->pilot);
    (new \Nemundo\Core\Debug\Debug())->write('Date: ' . $file->date->getIsoDateFormat());

    foreach ($file->getGeoCoordinateList() as $igcCoordinate) {
        (new \Nemundo\Core\Debug\Debug())->write($igcCoordinate);
        //(new \Nemundo\Core\Debug\Debug())->write($igcCoordinate->);

    }


    foreach ($file->getGeoCoordinateList() as $igcCoordinate) {
        (new \Nemundo\Core\Debug\Debug())->write($igcCoordinate);
        //(new \Nemundo\Core\Debug\Debug())->write($igcCoordinate->);

    }

    (new \Nemundo\Core\Debug\Debug())->write($file->inputList);

} else {
    (new \Nemundo\Core\Debug\Debug())->write('Igc File is not valid');
}
