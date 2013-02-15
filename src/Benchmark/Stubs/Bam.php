<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\BartInterface;

class Bam
{
    public $bart;

    /**
     * @param Benchmark\Stubs\Bart $bart
     */
    public function __construct(BartInterface $bart = null)
    {
        $this->bart = $bart;
    }

    public function setBart(BartInterface $bart)
    {
        $this->bart = $bart;
    }
}