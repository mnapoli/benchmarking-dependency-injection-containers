<?php

echo PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$bm = new Benchmark\Timer;

/*******************************************************************************
 Benchmark 3: Factory closure resolution.
 (Expecting this to be pretty much the same for each)
 Excluded: Zend, Symfony
********************************************************************************/

// Illuminate\Container (Laravel)
$bm->start('benchmark3', 'laravel');
$illuminate = new Illuminate\Container\Container;
$illuminate->bind('foo', function() {
    $bart = new Benchmark\Stubs\Bart;
    $bam = new Benchmark\Stubs\Bam($bart);
    $baz = new Benchmark\Stubs\Baz($bam);
    $bar = new Benchmark\Stubs\Bar($baz);
    return new Benchmark\Stubs\Foo($bar);
});
$foo = $illuminate->make('foo');
$bm->end('benchmark3', 'laravel');
unset($illuminate);
unset($foo);

// Orno\Di
$bm->start('benchmark3', 'orno');
$orno = new Orno\Di\Container;
$orno->register('foo', function() {
    $bart = new Benchmark\Stubs\Bart;
    $bam = new Benchmark\Stubs\Bam($bart);
    $baz = new Benchmark\Stubs\Baz($bam);
    $bar = new Benchmark\Stubs\Bar($baz);
    return new Benchmark\Stubs\Foo($bar);
});
$foo = $orno->resolve('foo');
$bm->end('benchmark3', 'orno');
unset($orno);
unset($foo);

// Pimple
$bm->start('benchmark3', 'pimple');
$pimple = new Pimple;
$pimple['foo'] = function() {
    $bart = new Benchmark\Stubs\Bart;
    $bam = new Benchmark\Stubs\Bam($bart);
    $baz = new Benchmark\Stubs\Baz($bam);
    $bar = new Benchmark\Stubs\Bar($baz);
    return new Benchmark\Stubs\Foo($bar);
};
$foo = $pimple['foo'];
$bm->end('benchmark3', 'pimple');
unset($pimple);
unset($foo);

?>