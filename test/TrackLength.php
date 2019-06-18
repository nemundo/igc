<?php


require __DIR__ . '/../../../config.php';

$filename = __DIR__ . '/igc/190331_NKN_01.igc';

$file = new \Nemundo\Igc\File\IgcFile($filename);
(new \Nemundo\Core\Debug\Debug())->write($file->getGeoCoordinateList());


$anaylzer = new \Nemundo\Igc\Analyzer\TrackLengthAnalyzer($file);
(new \Nemundo\Core\Debug\Debug())->write('Track Length: ' . $anaylzer->getTrackLength());
