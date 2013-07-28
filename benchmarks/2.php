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
 Benchmark 2: Auto resolution of object and dependencies.
 (Register all objects with container)
 Excluded: Pimple, Symfony
********************************************************************************/

for ($i = 0; $i < 1000; $i++) {

    // Illuminate\Container (Laravel)
    $illuminate = new Illuminate\Container\Container;
    $bm->start('benchmark2', 'laravel');
    $illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
    $illuminate->bind('Benchmark\Stubs\Bar');
    $illuminate->bind('Benchmark\Stubs\Bam');
    $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
    $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
    $foo = $illuminate->make('Foo');
    $bm->end('benchmark2', 'laravel');
    unset($illuminate);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // Orno\Di
    $orno = new Orno\Di\Container;
    $bm->start('benchmark2', 'orno');
    $orno->register('Benchmark\Stubs\Foo', 'Benchmark\Stubs\Foo', false, true);
    $orno->register('Benchmark\Stubs\Bar', 'Benchmark\Stubs\Bar', false, true);
    $orno->register('Benchmark\Stubs\Bam', 'Benchmark\Stubs\Bam', false, true);
    $orno->register('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz', false, true);
    $orno->register('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart', false, true);
    $foo = $orno->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark2', 'orno');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // League\Di
    $league = new League\Di\Container;
    $bm->start('benchmark2', 'league');
    $league->bind('Benchmark\Stubs\Foo');
    $league->bind('Benchmark\Stubs\Bar');
    $league->bind('Benchmark\Stubs\Bam');
    $league->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
    $league->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
    $foo = $league->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark2', 'league');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // Zend\Di
    $zend = new Zend\Di\Di;
    $bm->start('benchmark2', 'zend');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Foo', 'Benchmark\Stubs\Foo');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Bar', 'Benchmark\Stubs\Bar');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Bam', 'Benchmark\Stubs\Bam');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
    $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
    $foo = $zend->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark2', 'zend');
    unset($zend);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // PHP-DI
    $phpdiBuilder = new DI\ContainerBuilder();
    $phpdiBuilder->useReflection(false);
    $phpdiBuilder->useAnnotations(false);
    $phpdi = $phpdiBuilder->build();
    $bm->start('benchmark2', 'php-di');
    $phpdi->set('Benchmark\Stubs\Foo');
    $phpdi->set('Benchmark\Stubs\Bar');
    $phpdi->set('Benchmark\Stubs\Bam');
    $phpdi->set('Benchmark\Stubs\BazInterface')->bindTo('Benchmark\Stubs\Baz');
    $phpdi->set('Benchmark\Stubs\BartInterface')->bindTo('Benchmark\Stubs\Bart');
    $foo = $phpdi->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark2', 'php-di');
    unset($phpdi);
    unset($phpdiBuilder);
    unset($foo);

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark 2</title>

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
            ['Illuminate\\Container', <?= $bm->getBenchmarkTotal('benchmark2', 'laravel') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark2', 'orno') ?>],
            ['League\\Di', <?= $bm->getBenchmarkTotal('benchmark2', 'league') ?>],
            ['Zend\\Di', <?= $bm->getBenchmarkTotal('benchmark2', 'zend') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark2', 'php-di') ?>]
        ]);

        var options = {};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</body>
</html>
