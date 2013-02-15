<?php

echo PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$bm = new Benchmark\Timer;

/*******************************************************************************
 Benchmark 4: Constructor injection with defined arguments.
 Excluded: Laravel, Pimple, Zend
********************************************************************************/

// Orno\Di
$bm->start('benchmark4', 'orno');
$orno = new Orno\Di\Container;
$orno->register('Benchmark\Stubs\Bart');
$orno->register('Benchmark\Stubs\Bam')->withArgument($orno->resolve('Benchmark\Stubs\Bart'));
$orno->register('Benchmark\Stubs\Baz')->withArgument($orno->resolve('Benchmark\Stubs\Bam'));
$orno->register('Benchmark\Stubs\Bar')->withArgument($orno->resolve('Benchmark\Stubs\Baz'));
$orno->register('Benchmark\Stubs\Foo')->withArgument($orno->resolve('Benchmark\Stubs\Bar'));
$foo = $orno->resolve('Benchmark\Stubs\Foo');
$bm->end('benchmark4', 'orno');
unset($orno);
unset($foo);

// Symfony\DependencyInjection
$bm->start('benchmark4', 'symfony');
$symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
$symfony->register('foo', 'Benchmark\Stubs\Foo')->addArgument(new Symfony\Component\DependencyInjection\Reference('bar'));
$symfony->register('bar', 'Benchmark\Stubs\Bar')->addArgument(new Symfony\Component\DependencyInjection\Reference('baz'));
$symfony->register('baz', 'Benchmark\Stubs\Baz')->addArgument(new Symfony\Component\DependencyInjection\Reference('bam'));
$symfony->register('bam', 'Benchmark\Stubs\Bam')->addArgument(new Symfony\Component\DependencyInjection\Reference('bart'));
$symfony->register('bart', 'Benchmark\Stubs\Bart');
$foo = $symfony->get('foo');
$bm->end('benchmark4', 'symfony');
unset($symfony);
unset($foo);

?>