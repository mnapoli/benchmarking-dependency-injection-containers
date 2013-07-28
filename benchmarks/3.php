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
 Benchmark 3: Factory closure resolution.
 (Expecting this to be pretty much the same for each)
 Excluded: Zend, Symfony
********************************************************************************/

for ($i = 0; $i < 10000; $i++) {

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

}

for ($i = 0; $i < 10000; $i++) {

    // Orno\Di
    $orno = new Orno\Di\Container;
    $bm->start('benchmark3', 'orno');
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

}

for ($i = 0; $i < 10000; $i++) {

    // League\Di
    $league = new League\Di\Container;
    $bm->start('benchmark3', 'league');
    $league->bind('foo', function() {
        $bart = new Benchmark\Stubs\Bart;
        $bam = new Benchmark\Stubs\Bam($bart);
        $baz = new Benchmark\Stubs\Baz($bam);
        $bar = new Benchmark\Stubs\Bar($baz);
        return new Benchmark\Stubs\Foo($bar);
    });
    $foo = $league->resolve('foo');
    $bm->end('benchmark3', 'league');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 10000; $i++) {

    // Pimple
    $pimple = new Pimple;
    $bm->start('benchmark3', 'pimple');
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

}

for ($i = 0; $i < 10000; $i++) {

    // Aura.Di
    $aura = new Aura\Di\Container(new Aura\Di\Forge(new Aura\Di\Config));
    $bm->start('benchmark3', 'aura');
    $aura->set('foo', function() {
        $bart = new Benchmark\Stubs\Bart;
        $bam = new Benchmark\Stubs\Bam($bart);
        $baz = new Benchmark\Stubs\Baz($bam);
        $bar = new Benchmark\Stubs\Bar($baz);
        return new Benchmark\Stubs\Foo($bar);
    });
    $foo = $aura->get('foo');
    $bm->end('benchmark3', 'aura');
    unset($aura);
    unset($foo);

}

for ($i = 0; $i < 10000; $i++) {

    // PHP-DI
    $phpdiBuilder = new DI\ContainerBuilder();
    $phpdiBuilder->useReflection(false);
    $phpdiBuilder->useAnnotations(false);
    $phpdi = $phpdiBuilder->build();
    $bm->start('benchmark3', 'php-di');
    $phpdi->set('foo', function() {
        $bart = new Benchmark\Stubs\Bart;
        $bam = new Benchmark\Stubs\Bam($bart);
        $baz = new Benchmark\Stubs\Baz($bam);
        $bar = new Benchmark\Stubs\Bar($baz);
        return new Benchmark\Stubs\Foo($bar);
    });
    $foo = $phpdi->get('foo');
    $bm->end('benchmark3', 'php-di');
    unset($phpdi);
    unset($phpdiBuilder);
    unset($foo);

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark 3</title>

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
            ['Illuminate\\Container', <?= $bm->getBenchmarkTotal('benchmark3', 'laravel') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark3', 'orno') ?>],
            ['League\\Di', <?= $bm->getBenchmarkTotal('benchmark3', 'league') ?>],
            ['Pimple', <?= $bm->getBenchmarkTotal('benchmark3', 'pimple') ?>],
            ['Aura.Di', <?= $bm->getBenchmarkTotal('benchmark3', 'aura') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark3', 'php-di') ?>]
        ]);

        var options = {};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</body>
</html>
