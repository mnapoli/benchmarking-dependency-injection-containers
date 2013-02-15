<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\Bar;

class Foo
{
    public $bar;

    public function __construct(Bar $bar = null)
    {
        $this->bar = $bar;
    }

    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }
}