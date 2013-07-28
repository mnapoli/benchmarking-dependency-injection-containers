<?php

echo PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

// To stop autoloader caching skewing results
$bart = new Benchmark\Stubs\Bart;
$bam = new Benchmark\Stubs\Bam($bart);
$baz = new Benchmark\Stubs\Baz($bam);
$bar = new Benchmark\Stubs\Bar($baz);
$foo =  new Benchmark\Stubs\Foo($bar);

unset($foo);
unset($bar);
unset($baz);
unset($bam);
unset($bart);

$bm = new Benchmark\Timer;

/*******************************************************************************
 Benchmark 1: Auto resolution of object and dependencies.
 (Aliasing Interfaces to Concretes)
 Excluded: Pimple, Symfony
********************************************************************************/

for ($i = 0; $i < 1000; $i++) {

    // Illuminate\Container (Laravel)
    $illuminate = new Illuminate\Container\Container;
    $bm->start('benchmark1', 'laravel');
    $illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
    $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
    $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
    $foo = $illuminate->make('Foo');
    $bm->end('benchmark1', 'laravel');
    unset($illuminate);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // Orno\Di
    $orno = (new Orno\Di\Container);
    $bm->start('benchmark1', 'orno');
    $orno->register('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz', false, true);
    $orno->register('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart', false, true);
    $foo = $orno->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark1', 'orno');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // League\Di
    $league = new League\Di\Container;
    $bm->start('benchmark1', 'league');
    $league->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
    $league->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
    $foo = $league->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark1', 'league');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // Zend\Di
    $zend = new Zend\Di\Di;
    $bm->start('benchmark1', 'zend');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
    $foo = $zend->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark1', 'zend');
    unset($zend);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // PHP-DI
    $phpdiBuilder = new DI\ContainerBuilder();
    $phpdiBuilder->useAnnotations(false);
    $phpdi = $phpdiBuilder->build();
    $bm->start('benchmark1', 'php-di');
    $phpdi->set('Benchmark\Stubs\BazInterface')->bindTo('Benchmark\Stubs\Baz');
    $phpdi->set('Benchmark\Stubs\BartInterface')->bindTo('Benchmark\Stubs\Bart');
    $foo = $phpdi->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark1', 'php-di');
    unset($phpdi);
    unset($phpdiBuilder);
    unset($foo);

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark 1</title>

    <meta name="viewport" content="width-device-width, initial-scale=1">
</head>
<body>
    <div id="chart_div" style="width: 620px; height: 400px;"></div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Component', 'Time Taken'],
            ['Illuminate\\Container', <?= $bm->getBenchmarkTotal('benchmark1', 'laravel') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark1', 'orno') ?>],
            ['League\\Di', <?= $bm->getBenchmarkTotal('benchmark1', 'league') ?>],
            ['Zend\\Di', <?= $bm->getBenchmarkTotal('benchmark1', 'zend') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark1', 'php-di') ?>]
        ]);

        var options = {};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</body>
</html>
