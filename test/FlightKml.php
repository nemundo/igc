<?php

require __DIR__ . '/../../../config.php';


$filename = __DIR__ . '/igc/190331_NKN_01.igc';

$file = new \Nemundo\Igc\File\IgcFile($filename);


$kml = new \Nemundo\Geo\Kml\Document\KmlDocument();

$placemark = new \Nemundo\Geo\Kml\Container\Placemark($kml);
$placemark->label = 'Skyway';
$multi =new \Nemundo\Geo\Kml\Container\MultiGeometry($placemark);

$reducer = new \Nemundo\Igc\Optimizer\CoordinateReducer($file);
$reducer->minVerticalDistance = 20;
$reducer->minHorizontalDistance = 100;

$analyzer = new \Nemundo\Igc\Kml\FlightKml($reducer);
$line = $content = $analyzer->getKml();

$multi->addContainer($line);

$kml->render();
