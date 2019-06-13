<?php

namespace Nemundo\Igc\Reader;


class RawIgcReader extends AbstractIgcReader
{

    /**
     * @var string
     */
    public $filename;


    public function getList()
    {

        $this->loadData();
        return $this->list;

    }


}