<?php

namespace Nemundo\Igc\Analyzer;


use Nemundo\Core\Base\AbstractBase;

use Nemundo\Igc\Reader\AbstractIgcReader;

class AbstractIgcAnalyzer extends AbstractBase
{

    /**
     * @var IgcReader
     */
    protected $igcReader;

    public function __construct(AbstractIgcReader $igcReader)
    {
        $this->igcReader = $igcReader;
    }

}