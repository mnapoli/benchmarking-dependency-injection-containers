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
 Benchmark 5: Setter injection with defined setter methods.
 Excluded: Laravel, Pimple, Zend
********************************************************************************/

for ($i = 0; $i < 1000; $i++) {

    // Symfony\DependencyInjection
    $bm->start('benchmark5', 'symfony');
    $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
    $symfony->register('foo', 'Benchmark\Stubs\Foo')->addMethodCall('setBar', [new Symfony\Component\DependencyInjection\Reference('bar')]);
    $symfony->register('bar', 'Benchmark\Stubs\Bar')->addMethodCall('setBaz', [new Symfony\Component\DependencyInjection\Reference('baz')]);
    $symfony->register('baz', 'Benchmark\Stubs\Baz')->addMethodCall('setBam', [new Symfony\Component\DependencyInjection\Reference('bam')]);
    $symfony->register('bam', 'Benchmark\Stubs\Bam')->addMethodCall('setBart', [new Symfony\Component\DependencyInjection\Reference('bart')]);
    $symfony->register('bart', 'Benchmark\Stubs\Bart');
    $foo = $symfony->get('foo');
    $bm->end('benchmark5', 'symfony');
    unset($symfony);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // Orno\Di
    $orno = new Orno\Di\Container;
    $bm->start('benchmark5', 'orno');
    $orno->register('Benchmark\Stubs\Bart');
    $orno->register('Benchmark\Stubs\Bam')->withMethodCall('setBart', ['Benchmark\Stubs\Bart']);
    $orno->register('Benchmark\Stubs\Baz')->withMethodCall('setBam', ['Benchmark\Stubs\Bam']);
    $orno->register('Benchmark\Stubs\Bar')->withMethodCall('setBaz', ['Benchmark\Stubs\Baz']);
    $orno->register('Benchmark\Stubs\Foo')->withMethodCall('setBar', ['Benchmark\Stubs\Bar']);
    $foo = $orno->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'orno');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // League\Di
    $league = new League\Di\Container;
    $bm->start('benchmark5', 'league');
    $league->bind('Benchmark\Stubs\Bart');
    $league->bind('Benchmark\Stubs\Bam')->withMethod('setBart', ['Benchmark\Stubs\Bart']);
    $league->bind('Benchmark\Stubs\Baz')->withMethod('setBam', ['Benchmark\Stubs\Bam']);
    $league->bind('Benchmark\Stubs\Bar')->withMethod('setBaz', ['Benchmark\Stubs\Baz']);
    $league->bind('Benchmark\Stubs\Foo')->withMethod('setBar', ['Benchmark\Stubs\Bar']);
    $foo = $league->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'league');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // Aura.Di
    $aura = new Aura\Di\Container(new Aura\Di\Forge(new Aura\Di\Config));
    $bm->start('benchmark5', 'aura');
    $aura->setter['Benchmark\Stubs\Bam']['setBart'] = $aura->lazyNew('Benchmark\Stubs\Bart');
    $aura->setter['Benchmark\Stubs\Baz']['setBam'] = $aura->lazyNew('Benchmark\Stubs\Bam');
    $aura->setter['Benchmark\Stubs\Bar']['setBaz'] = $aura->lazyNew('Benchmark\Stubs\Baz');
    $aura->setter['Benchmark\Stubs\Foo']['setBar'] = $aura->lazyNew('Benchmark\Stubs\Bar');
    $foo = $aura->newInstance('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'aura');
    unset($aura);
    unset($foo);

}

for ($i = 0; $i < 1000; $i++) {

    // PHP-DI
    $phpdiBuilder = new DI\ContainerBuilder();
    $phpdiBuilder->useReflection(false);
    $phpdiBuilder->useAnnotations(false);
    $phpdi = $phpdiBuilder->build();
    $bm->start('benchmark5', 'php-di');
    $phpdi->set('Benchmark\Stubs\Bart');
    $phpdi->set('Benchmark\Stubs\Bam')->withMethod('setBart', array('Benchmark\Stubs\Bart'));
    $phpdi->set('Benchmark\Stubs\Baz')->withMethod('setBam', array('Benchmark\Stubs\Bam'));
    $phpdi->set('Benchmark\Stubs\Bar')->withMethod('setBaz', array('Benchmark\Stubs\Baz'));
    $phpdi->set('Benchmark\Stubs\Foo')->withMethod('setBar', array('Benchmark\Stubs\Bar'));
    $foo = $phpdi->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'php-di');
    unset($phpdi);
    unset($phpdiBuilder);
    unset($foo);

}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark 5</title>

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
            ['Symfony\\DependencyInjection', <?= $bm->getBenchmarkTotal('benchmark5', 'symfony') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark5', 'orno') ?>],
            ['League\\Di', <?= $bm->getBenchmarkTotal('benchmark5', 'league') ?>],
            ['Aura.Di', <?= $bm->getBenchmarkTotal('benchmark5', 'aura') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark5', 'php-di') ?>]
        ]);

        var options = {};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</body>
</html>
