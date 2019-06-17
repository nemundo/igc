<?php

namespace Nemundo\Igc\Reader;


class RawIgcReader extends AbstractRawIgcReader
{

    /**
     * @var string
     */
    public $filename;


    public function getInputList()
    {

        $this->loadData();
        return $this->inputList;

    }


}