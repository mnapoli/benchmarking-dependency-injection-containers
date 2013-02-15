<?php namespace Benchmark;

class Timer
{
    protected $benchmarks = [];

    public function start($benchmark, $component)
    {
        $this->benchmarks[$benchmark][$component]['start'][] = microtime(true);
    }

    public function end($benchmark, $component)
    {
        $this->benchmarks[$benchmark][$component]['end'][] = microtime(true);
        $this->benchmarks[$benchmark][$component]['time'][] = number_format(end($this->benchmarks[$benchmark][$component]['end']) - end($this->benchmarks[$benchmark][$component]['start']), 6);
    }

    public function getBenchmarkData($benchmark)
    {
        return $this->benchmarks[$benchmark];
    }
}