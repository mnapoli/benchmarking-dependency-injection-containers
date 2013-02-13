<?php namespace Benchmark;

class Timer
{
    public $start;

    public function startBM()
    {
        unset($this->start);
        $this->start = microtime(true);
    }

    public function endBM()
    {
        return number_format(microtime(true) - $start, 8);
    }
}