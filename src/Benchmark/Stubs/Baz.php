<?php namespace Benchmark\Stubs;

use Benchmark\Stubs\BazInterface,
    Benchmark\Stubs\Bam;

class Baz implements BazInterface
{
    public $bam;

    public function __construct(Bam $bam = null)
    {
        $this->bam = $bam;
    }

    public function setBam(Bam $bam)
    {
        $this->bam = $bam;
    }

    public function requiredMethodFromBazInterface()
    {
        echo 'Baz says relax!';
    }
}