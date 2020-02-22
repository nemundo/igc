<?php

require __DIR__ . '/../../../config.php';

//$filename = __DIR__.'/igc/190331_NKN_01.igc';

$filename = 'C:\Data\Corrupt Igc\2019-05-23-XCT-AMA-03.igc';


$reader = new \Nemundo\Igc\IgcReader($filename);


/*
(new \Nemundo\Core\Debug\Debug())->write('Coordinate Count: '.$reader->coordinateCount);
(new \Nemundo\Core\Debug\Debug())->write('Invalid Coordinate Count: '.$reader->invalidCoordinateCount);
(new \Nemundo\Core\Debug\Debug())->write('Valid Coordinate Count: '.$reader->validCoordinateCount);
*/


$kml = new \Nemundo\Geo\Kml\Document\KmlDocument();

$line = new \Nemundo\Geo\Kml\Object\KmlLine($kml);


foreach ($reader->getGeoCoordinateList() as $igcCoordinate) {
        $line->addPoint($igcCoordinate);
}

$kml->render();


