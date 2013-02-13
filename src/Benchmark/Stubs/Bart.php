<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\BartInterface;

class Bart implements BartInterface
{
    public function requiredMethodFromBartInterface()
    {
        echo 'Bart says cowabunga!';
    }
}