<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\BazInterface;

class Bar
{
    public $baz;

    /**
     * @param Benchmark\Stubs\Baz $baz
     */
    public function __construct(BazInterface $baz = null)
    {
        $this->baz = $baz;
    }

    public function setBaz(BazInterface $baz)
    {
        $this->baz = $baz;
    }
}