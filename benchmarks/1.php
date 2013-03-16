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

for ($i = 0; $i < 1000; $i++) {

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

    // PHP-DI
    $bm->start('benchmark1', 'php-di');
    DI\Container::reset();
    $phpdi = DI\Container::getInstance();
    DI\Container::addConfiguration(
        array(
            "aliases" => array(
                'Benchmark\Stubs\BazInterface' => 'Benchmark\Stubs\Baz',
                'Benchmark\Stubs\BartInterface' => 'Benchmark\Stubs\Bart',
            ),
        )
    );
    $foo = $phpdi->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark1', 'php-di');
    unset($phpdi);
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
    <div id="chart_div" style="width: 980px; height: 650px;"></div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Component', 'Time Taken'],
            ['Illuminate\\Container (Laravel)', <?= $bm->getBenchmarkTotal('benchmark1', 'laravel') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark1', 'orno') ?>],
            ['Zend\\Di', <?= $bm->getBenchmarkTotal('benchmark1', 'zend') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark1', 'php-di') ?>]
        ]);

        var options = {
            hAxis: {title: 'Component', titleTextStyle: {color: 'red'}},
            vAxis: {title: 'Time Taken (Seconds)', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</body>
</html>
