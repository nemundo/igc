<?php


require __DIR__.'/../../../config.php';

$filename = __DIR__.'/igc/190331_NKN_01.igc';


$reader = new \Nemundo\Igc\Reader\RawIgcReader();
$reader->filename = $filename;

foreach ($reader->getInputList() as $item) {
    (new \Nemundo\Core\Debug\Debug())->write($item);
}
