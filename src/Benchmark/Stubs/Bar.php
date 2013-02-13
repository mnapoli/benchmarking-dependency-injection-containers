<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\BazInterface;

class Bar
{
    public $baz;

    /**
     * @param Benchmark\Stubs\Baz $baz
     */
    public function __construct(BazInterface $baz)
    {
        $this->baz = $baz;
    }
}