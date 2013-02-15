<?php

echo PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$bm = new Benchmark\Timer;

/*******************************************************************************
 Benchmark 1: Auto resolution of object and dependencies.
 (Aliasing Interfaces to Concretes)
 Excluded: Pimple, Symfony
********************************************************************************/

// Illuminate\Container (Laravel)
$bm->start('benchmark1', 'laravel');
$illuminate = new Illuminate\Container\Container;
$illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
$illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
$illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
$foo = $illuminate->make('Foo');
$bm->end('benchmark1', 'laravel');
unset($illuminate);
unset($foo);

// Orno\Di
$bm->start('benchmark1', 'orno');
$orno = (new Orno\Di\Container)->autoResolve(true);
$orno->register('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
$orno->register('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
$foo = $orno->resolve('Benchmark\Stubs\Foo');
$bm->end('benchmark1', 'orno');
unset($orno);
unset($foo);

// Zend\Di
$bm->start('benchmark1', 'zend');
$zend = new Zend\Di\Di;
$zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
$zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
$foo = $zend->get('Benchmark\Stubs\Foo');
$bm->end('benchmark1', 'zend');
unset($zend);
unset($foo);

?>