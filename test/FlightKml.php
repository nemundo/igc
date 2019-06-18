<?php


require __DIR__ . '/../../../config.php';

//$filename = 'C:\Data\Corrupt Igc\2019-05-23-XCT-AMA-03.igc';


$filename = __DIR__ . '/igc/190331_NKN_01.igc';

$file = new \Nemundo\Igc\File\IgcFile($filename);


//$reader = new \Nemundo\Igc\Reader\IgcReader();
//$reader->filename = $filename;

//$anaylzer = new \Nemundo\Igc\Analyzer\TrackLengthAnalyzer($file);
//(new \Nemundo\Core\Debug\Debug())->write('Track Length: '. $anaylzer->getTrackLength());

//(new \Nemundo\Core\Debug\Debug())->write((new \Nemundo\Igc\Analyzer\ThermalKml($reader))->getKml());





//$analyzer = new \Nemundo\Igc\Kml\ThermalKml($file);
//$analyzer->minDistance = 1;

//$content = $analyzer->getKml();


$kml = new \Nemundo\Geo\Kml\Document\KmlDocument();

/*
$placemark = new \Nemundo\Geo\Kml\Container\Placemark($kml);
$placemark->label = 'Thermal';
$multi =new \Nemundo\Geo\Kml\Container\MultiGeometry($placemark);

$container = new \Nemundo\Html\Container\Container($multi);
$container->addContent($content);
*/



$placemark = new \Nemundo\Geo\Kml\Container\Placemark($kml);
$placemark->label = 'Skyway';
$multi =new \Nemundo\Geo\Kml\Container\MultiGeometry($placemark);


$reducer = new \Nemundo\Igc\Optimizer\CoordinateReducer($file);
$reducer->minVerticalDistance = 100;
$reducer->minHorizontalDistance = 1000;

$analyzer = new \Nemundo\Igc\Kml\FlightKml($reducer);
$line = $content = $analyzer->getKml();

$multi->addContainer($line);

//$container = new \Nemundo\Html\Container\Container($multi);
//$container->addContent($content);

$kml->render();
