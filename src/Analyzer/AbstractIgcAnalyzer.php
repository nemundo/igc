<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Core\Base\AbstractBase;

use Nemundo\Igc\File\IgcFile;
use Nemundo\Igc\Reader\AbstractIgcReader;
use Nemundo\Igc\Reader\IgcReader;
use Nemundo\Igc\Source\AbstractCoordinateSource;
use Nemundo\Igc\Source\AbstractSource;
use Nemundo\Igc\Source\LoadSourceTrait;

abstract class AbstractIgcAnalyzer extends AbstractBase  // AbstractSource   // AbstractCoordinateSource  // AbstractBase
{

    use LoadSourceTrait;

    /**
     * @var AbstractCoordinateSource
     */
   // protected $source;
/*
    public function __construct(AbstractCoordinateSource $source)
    {
        $this->source = $source;
    }*/

}