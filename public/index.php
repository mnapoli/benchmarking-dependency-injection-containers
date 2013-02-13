<?php

include __DIR__ . '/../vendor/orno/loader/library/Orno/Loader/Autoloader.php';

$classes = [
    'Pimple' => __DIR__ . '/../vendor/pimple/pimple/lib/Pimple.php'
];

$namespaces = [
    'Orno\Di'              => __DIR__ . '/../vendor/di/orno/src/Orno/Di',
    'Illuminate\Container' => __DIR__ . '/../vendor/illuminate/container/Illuminate/Container',
    'Zend\Di'              => __DIR__ . '/../vendor/zendframework/zend-di/Zend/Di',
    'Zend\Code'            => __DIR__ . '/../vendor/zendframework/zend-code/Zend/Code',
    'Zend\EventManager'    => __DIR__ . '/../vendor/zendframework/zend-di/Zend/EventManager',
    'Zend\Stdlib'          => __DIR__ . '/../vendor/zendframework/zend-di/Zend/Stdlib',
    'Benchmark'            => __DIR__ . '/../src/Benchmark'
];

(new Orno\Loader\Autoloader)->registerClasses($classes)
                            ->registerNamespaces($namespaces)
                            ->register();

$bm = new Benchmark\Timer;