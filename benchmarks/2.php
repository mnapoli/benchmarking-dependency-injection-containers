<?php

echo PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$bm = new Benchmark\Timer;

/*******************************************************************************
 Benchmark 2: Auto resolution of object and dependencies.
 (Register all dependencies with container)
 Excluded: Pimple, Symfony
********************************************************************************/

// Illuminate\Container (Laravel)
$bm->start('benchmark2', 'laravel');
$illuminate = new Illuminate\Container\Container;
$illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
$illuminate->bind('Benchmark\Stubs\Bar');
$illuminate->bind('Benchmark\Stubs\Bam');
$illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
$illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
$foo = $illuminate->make('Foo');
$bm->end('benchmark2', 'laravel');
unset($illuminate);
unset($foo);

// Orno\Di
$bm->start('benchmark2', 'orno');
$orno = (new Orno\Di\Container)->autoResolve(true);
$orno->register('Benchmark\Stubs\Foo');
$orno->register('Benchmark\Stubs\Bar');
$orno->register('Benchmark\Stubs\Bam');
$orno->register('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
$orno->register('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
$foo = $orno->resolve('Benchmark\Stubs\Foo');
$bm->end('benchmark2', 'orno');
unset($orno);
unset($foo);

// Zend\Di
$bm->start('benchmark2', 'zend');
$zend = new Zend\Di\Di;
$zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
$zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
$zend->instanceManager()->setInjections('Benchmark\Stubs\Foo', ['Benchmark\Stubs\Bar']);
$zend->instanceManager()->setInjections('Benchmark\Stubs\Bar', ['Benchmark\Stubs\Baz']);
$zend->instanceManager()->setInjections('Benchmark\Stubs\Baz', ['Benchmark\Stubs\Bam']);
$zend->instanceManager()->setInjections('Benchmark\Stubs\Bam', ['Benchmark\Stubs\Bart']);
$foo = $zend->get('Benchmark\Stubs\Foo');
$bm->end('benchmark2', 'zend');
unset($zend);
unset($foo);

?>