<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\Bar;
use DI\Annotations\Inject;

class FooAnnotated
{
    public $bar;

    /**
     * @Inject
     * @param Bar $bar
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }
}
