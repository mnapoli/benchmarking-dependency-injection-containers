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

for ($i = 0; $i < 100; $i++) {

    // Orno\Di
    $orno = new Orno\Di\Container;
    $bm->start('benchmark4', 'orno');
    $orno->register('Benchmark\Stubs\Bart');
    $orno->register('Benchmark\Stubs\Bam')->withArgument('Benchmark\Stubs\Bart');
    $orno->register('Benchmark\Stubs\Baz')->withArgument('Benchmark\Stubs\Bam');
    $orno->register('Benchmark\Stubs\Bar')->withArgument('Benchmark\Stubs\Baz');
    $orno->register('Benchmark\Stubs\Foo')->withArgument('Benchmark\Stubs\Bar');
    $foo = $orno->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark4', 'orno');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 100; $i++) {

    // League\Di
    $league = new League\Di\Container;
    $bm->start('benchmark4', 'league');
    $league->bind('Benchmark\Stubs\Bart');
    $league->bind('Benchmark\Stubs\Bam')->addArg('Benchmark\Stubs\Bart');
    $league->bind('Benchmark\Stubs\Baz')->addArg('Benchmark\Stubs\Bam');
    $league->bind('Benchmark\Stubs\Bar')->addArg('Benchmark\Stubs\Baz');
    $league->bind('Benchmark\Stubs\Foo')->addArg('Benchmark\Stubs\Bar');
    $foo = $league->resolve('Benchmark\Stubs\Foo');
    $bm->end('benchmark4', 'league');
    unset($orno);
    unset($foo);

}

for ($i = 0; $i < 100; $i++) {

    // Symfony\DependencyInjection
    $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
    $bm->start('benchmark4', 'symfony');
    $symfony->register('foo', 'Benchmark\Stubs\Foo')->addArgument(new Symfony\Component\DependencyInjection\Reference('bar'));
    $symfony->register('bar', 'Benchmark\Stubs\Bar')->addArgument(new Symfony\Component\DependencyInjection\Reference('baz'));
    $symfony->register('baz', 'Benchmark\Stubs\Baz')->addArgument(new Symfony\Component\DependencyInjection\Reference('bam'));
    $symfony->register('bam', 'Benchmark\Stubs\Bam')->addArgument(new Symfony\Component\DependencyInjection\Reference('bart'));
    $symfony->register('bart', 'Benchmark\Stubs\Bart');
    $foo = $symfony->get('foo');
    $bm->end('benchmark4', 'symfony');
    unset($symfony);
    unset($foo);

}

for ($i = 0; $i < 100; $i++) {

    // Zend\Di
    $zend = new Zend\Di\Di;
    $bm->start('benchmark4', 'zend');
    $zend->instanceManager()->setInjections('Benchmark\Stubs\Foo', ['Benchmark\Stubs\Bar']);
    $zend->instanceManager()->setInjections('Benchmark\Stubs\Bar', ['Benchmark\Stubs\Baz']);
    $zend->instanceManager()->setInjections('Benchmark\Stubs\Baz', ['Benchmark\Stubs\Bam']);
    $zend->instanceManager()->setInjections('Benchmark\Stubs\Bam', ['Benchmark\Stubs\Bart']);
    $foo = $zend->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark4', 'zend');
    unset($zend);
    unset($foo);

}

for ($i = 0; $i < 100; $i++) {

    // Aura.Di
    $aura = new Aura\Di\Container(new Aura\Di\Forge(new Aura\Di\Config));
    $bm->start('benchmark4', 'aura');
    $aura->params['Benchmark\Stubs\Bam'] = ['bart' => $aura->lazyNew('Benchmark\Stubs\Bart')];
    $aura->params['Benchmark\Stubs\Baz'] = ['bam' => $aura->lazyNew('Benchmark\Stubs\Bam')];
    $aura->params['Benchmark\Stubs\Bar'] = ['baz' => $aura->lazyNew('Benchmark\Stubs\Baz')];
    $aura->params['Benchmark\Stubs\Foo'] = ['bar' => $aura->lazyNew('Benchmark\Stubs\Bar')];
    $foo = $aura->newInstance('Benchmark\Stubs\Foo');
    $bm->end('benchmark4', 'aura');
    unset($aura);
    unset($foo);

}

for ($i = 0; $i < 100; $i++) {

    // PHP-DI
    $phpdiBuilder = new DI\ContainerBuilder();
    $phpdiBuilder->useReflection(false);
    $phpdiBuilder->useAnnotations(false);
    $phpdi = $phpdiBuilder->build();
    $bm->start('benchmark4', 'php-di');
    $phpdi->set('Benchmark\Stubs\Bart');
    $phpdi->set('Benchmark\Stubs\Bam')->withConstructor(array('Benchmark\Stubs\Bart'));
    $phpdi->set('Benchmark\Stubs\Baz')->withConstructor(array('Benchmark\Stubs\Bam'));
    $phpdi->set('Benchmark\Stubs\Bar')->withConstructor(array('Benchmark\Stubs\Baz'));
    $phpdi->set('Benchmark\Stubs\Foo')->withConstructor(array('Benchmark\Stubs\Bar'));
    $foo = $phpdi->get('Benchmark\Stubs\Foo');
    $bm->end('benchmark4', 'php-di');
    unset($phpdi);
    unset($phpdiBuilder);
    unset($foo);

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark 4</title>

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
            ['Symfony\\DependencyInjection', <?= $bm->getBenchmarkTotal('benchmark4', 'symfony') ?>],
            ['Orno\\Di', <?= $bm->getBenchmarkTotal('benchmark4', 'orno') ?>],
            ['League\\Di', <?= $bm->getBenchmarkTotal('benchmark4', 'league') ?>],
            ['Zend\\Di', <?= $bm->getBenchmarkTotal('benchmark4', 'zend') ?>],
            ['Aura.Di', <?= $bm->getBenchmarkTotal('benchmark4', 'aura') ?>],
            ['PHP-DI', <?= $bm->getBenchmarkTotal('benchmark4', 'php-di') ?>]
        ]);

        var options = {};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</body>
</html>
