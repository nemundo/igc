<?php

namespace Nemundo\Igc\Source;


trait LoadSourceTrait
{

    /**
     * @var AbstractSource
     */
    protected $source;

    public function __construct(AbstractSource $source)
    {
        $this->source = $source;
    }

}