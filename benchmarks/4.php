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
 Benchmark 4: Constructor injection with defined arguments.
 Excluded: Laravel, Pimple, Zend
********************************************************************************/

// Orno\Di
$bm->start('benchmark4', 'orno');
$orno = new Orno\Di\Container;
$orno->register('Benchmark\Stubs\Bart');
$orno->register('Benchmark\Stubs\Bam')->withArgument('Benchmark\Stubs\Bart');
$orno->register('Benchmark\Stubs\Baz')->withArgument('Benchmark\Stubs\Bam');
$orno->register('Benchmark\Stubs\Bar')->withArgument('Benchmark\Stubs\Baz');
$orno->register('Benchmark\Stubs\Foo')->withArgument('Benchmark\Stubs\Bar');
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

// Zend\Di
$bm->start('benchmark4', 'zend');
$zend = new Zend\Di\Di;
$zend->instanceManager()->setInjections('Benchmark\Stubs\Foo', ['Benchmark\Stubs\Bar']);
$zend->instanceManager()->setInjections('Benchmark\Stubs\Bar', ['Benchmark\Stubs\Baz']);
$zend->instanceManager()->setInjections('Benchmark\Stubs\Baz', ['Benchmark\Stubs\Bam']);
$zend->instanceManager()->setInjections('Benchmark\Stubs\Bam', ['Benchmark\Stubs\Bart']);
$foo = $zend->get('Benchmark\Stubs\Foo');
$bm->end('benchmark4', 'zend');
unset($zend);
unset($foo);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark 4</title>

    <meta name="viewport" content="width-device-width, initial-scale=1">
</head>
<body>
    <div id="chart_div" style="width: 800px; height: 500px;"></div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Component', 'Time Taken'],
            ['Symfony\\DependencyInjection', <?= $bm->getBenchmarkData('benchmark4')['symfony']['time'][0] ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkData('benchmark4')['orno']['time'][0] ?>],
            ['Zend\\Di', <?= $bm->getBenchmarkData('benchmark4')['zend']['time'][0] ?>]
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
