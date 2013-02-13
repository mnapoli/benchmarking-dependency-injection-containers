<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\Bar;

class Foo
{
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}