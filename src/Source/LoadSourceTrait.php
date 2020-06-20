<?php

namespace Nemundo\Igc\Source;


trait LoadSourceTrait
{

    /**
     * @var AbstractSource
     */
    protected $source;

    // IgcFile

    public function __construct(AbstractSource $source)
    {
        $this->source = $source;
    }

}