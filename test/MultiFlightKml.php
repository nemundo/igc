<?php

require __DIR__ . '/../../../config.php';

$path = 'C:\Data\Pwc\igc';

$kml = new \Nemundo\Geo\Kml\Document\KmlDocument();


$thermal = new \Nemundo\Geo\Kml\Container\Placemark($kml);
$thermal->label = 'Thermal';

$multi = new \Nemundo\Geo\Kml\Container\MultiGeometry($thermal);



$reader = new \Nemundo\Core\File\DirectoryReader();
$reader->path = $path;
$reader->includeFiles = true;
$reader->includeDirectories = false;
$reader->recursiveSearch = true;
$reader->addFileExtensionFilter('igc');
foreach ($reader->getData() as $file) {


    $file = new \Nemundo\Igc\File\IgcFile($file->fullFilename);


    //$igcReader->getFlightDate();

    $placemark = new \Nemundo\Geo\Kml\Container\Placemark($kml);
    $placemark->label = $file->pilot;  //  'Skyway';


    //$multi =new \Nemundo\Geo\Kml\Container\MultiGeometry($placemark);

    $reducer = new \Nemundo\Igc\Optimizer\CoordinateReducer($file);  // new \Paranautik\App\Flight\Analyzer\Coordinate\CoordinateReducer();
    //$reducer->minVerticalDistance = 10;
    //$reducer->minHorizontalDistance = 1000;


    $analyzer = new \Nemundo\Igc\Kml\FlightKml($reducer);
    $line =  $analyzer->getKml();

    $placemark->addContainer($line);


    $thermal = new \Nemundo\Igc\Kml\ThermalKml($file);
    $thermal->getKml($multi);


//$container = new \Nemundo\Html\Container\Container($multi);
//$container->addContent($content);



}

$kml->render();
