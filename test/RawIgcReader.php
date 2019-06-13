<?php


require __DIR__.'/../../../config.php';

$filename = 'C:\Data\Corrupt Igc\2019-05-23-XCT-AMA-03.igc';


$reader = new \Nemundo\Igc\Reader\RawIgcReader();
$reader->filename = $filename;

foreach ($reader->getList() as $item) {
    (new \Nemundo\Core\Debug\Debug())->write($item);
}
