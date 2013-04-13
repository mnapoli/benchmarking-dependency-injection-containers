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
     Benchmark 5: Setter injection with defined setter methods.
     Excluded: Laravel, Pimple, Zend
    ********************************************************************************/

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

    // Orno\Di
    $bm->start('benchmark5', 'orno');
    $orno = new Orno\Di\Container;
    $orno->register('Benchmark\Stubs\Bart');
    $orno->register('Benchmark\Stubs\Bam')->withMethodCall('setBart', ['Benchmark\Stubs\Bart']);
    $orno->register('Benchmark\Stubs\Baz')->withMethodCall('setBam', ['Benchmark\Stubs\Bam']);
    $orno->register('Benchmark\Stubs\Bar')->withMethodCall('setBaz', ['Benchmark\Stubs\Baz']);
    $orno->register('Benchmark\Stubs\Foo')->withMethodCall('setBar', ['Benchmark\Stubs\Bar']);
    $foo = $orno->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'orno');
    unset($orno);
    unset($foo);

    // Aura.Di
    $bm->start('benchmark5', 'aura');
    $aura = new Aura\Di\Container(new Aura\Di\Forge(new Aura\Di\Config));
    $aura->setter['Benchmark\Stubs\Bam']['setBart'] = $aura->lazyNew('Benchmark\Stubs\Bart');
    $aura->setter['Benchmark\Stubs\Baz']['setBam'] = $aura->lazyNew('Benchmark\Stubs\Bam');
    $aura->setter['Benchmark\Stubs\Bar']['setBaz'] = $aura->lazyNew('Benchmark\Stubs\Baz');
    $aura->setter['Benchmark\Stubs\Foo']['setBar'] = $aura->lazyNew('Benchmark\Stubs\Bar');
    $foo = $aura->newInstance('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'aura');
    unset($aura);
    unset($foo);

    // PHP-DI
    $bm->start('benchmark5', 'php-di');
    $phpdi = new DI\Container();
    $phpdi->useReflection(false);
    $phpdi->useAnnotations(false);
    $phpdi->addDefinitions(
        array(
             'Benchmark\Stubs\Bart'  => array(
                 'class' => 'Benchmark\Stubs\Bart'
             ),
             'Benchmark\Stubs\Bam'  => array(
                 'methods' => array(
                     'setBart' => 'Benchmark\Stubs\Bart',
                 ),
             ),
             'Benchmark\Stubs\Baz'  => array(
                 'methods' => array(
                     'setBam' => 'Benchmark\Stubs\Bam',
                 ),
             ),
             'Benchmark\Stubs\Bar'  => array(
                 'methods' => array(
                     'setBaz' => 'Benchmark\Stubs\Baz',
                 ),
             ),
             'Benchmark\Stubs\Foo'  => array(
                 'methods' => array(
                     'setBar' => 'Benchmark\Stubs\Bar',
                 ),
             ),
        )
    );
    $foo = $phpdi->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark5', 'php-di');
    unset($phpdi);
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
    <div id="chart_div" style="width: 980px; height: 650px;"></div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Component', 'Time Taken'],
            ['Symfony\\DependencyInjection', <?= $bm->getBenchmarkTotal('benchmark5', 'symfony') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark5', 'orno') ?>],
            ['Aura.Di', <?= $bm->getBenchmarkTotal('benchmark5', 'aura') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark5', 'php-di') ?>]
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
