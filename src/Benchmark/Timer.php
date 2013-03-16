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
        $this->benchmarks[$benchmark][$component]['time'][] = end($this->benchmarks[$benchmark][$component]['end']) - end($this->benchmarks[$benchmark][$component]['start']);
    }

    public function getBenchmarkData($benchmark)
    {
        return $this->benchmarks[$benchmark];
    }

    public function getBenchmarkTotal($benchmark, $component)
    {
        $total = 0;
        foreach ($this->benchmarks[$benchmark][$component]['time'] as $number) {
            $total += $number;
        }
        $total = $total / 1000;
        return number_format($total, 6);
    }
}
