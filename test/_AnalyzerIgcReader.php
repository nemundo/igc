<?php


// AbstractFlight


require __DIR__ . '/../../../config.php';

$filename = 'C:\Data\Corrupt Igc\2019-05-23-XCT-AMA-03.igc';


//$kml = new \Nemundo\Geo\Kml\Document\KmlDocument();


$reader = new \Nemundo\Igc\Reader\IgcReader();
$reader->filename = $filename;

$anaylzer = new \Nemundo\Igc\Analyzer\TrackLengthAnalyzer($reader);
(new \Nemundo\Core\Debug\Debug())->write('Track Length: '. $anaylzer->getTrackLength());
(new \Nemundo\Core\Debug\Debug())->write((new \Nemundo\Igc\Analyzer\ThermalKml($reader))->getKml());


/*


$marker = new \Nemundo\Geo\Kml\Object\KmlMarker($kml);
$marker->coordinate = $reader->getTakeoff();



$marker = new \Nemundo\Geo\Kml\Object\KmlMarker($kml);
$marker->coordinate = $reader->getLanding();


/*
$line = new \Nemundo\Geo\Kml\Object\KmlLine($kml);
$line->label = 'Raw Line';
foreach ($reader->getGeoCoordinateList() as $coordinate) {
    $line->addPoint($coordinate);
}*/


/*
$line = new \Nemundo\Geo\Kml\Object\KmlLine($kml);
$line->label = 'Cleaned Line';
foreach ($reader->getCleanedGeoCoordinateList() as $coordinate) {
    $line->addPoint($coordinate);
    //(new \Nemundo\Core\Debug\Debug())->write($coordinate);
}






//(new \Nemundo\Core\Debug\Debug())->write($reader->getKmlCoordinate());





/*
$kml = new \Nemundo\Geo\Kml\Document\KmlDocument();

$line = new \Nemundo\Geo\Kml\Object\KmlLine($kml);
$line->label = 'Raw Line';
foreach ($reader->getGeoCoordinateList() as $coordinate) {
    $line->addPoint($coordinate);
}

$line = new \Nemundo\Geo\Kml\Object\KmlLine($kml);
$line->label = 'Cleaned Line';
foreach ($reader->getCleanedGeoCoordinateList() as $coordinate) {
    $line->addPoint($coordinate);
}*/


//$kml->render();