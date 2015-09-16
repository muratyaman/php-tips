<?php

$t0 = microtime(true);

$arr = [];

$L = 1000000;
$S = 'abcdefghijklmnopqrstuvwxyz';
mt_srand();
for($i = 1; $i <= $L; $i++) {
    $row = [
        'a' => str_shuffle($S),//string
        'b' => mt_rand(0, $L) / mt_rand(1, $L),//float
        'c' => mt_rand(-1000, 1000),//int
    ];
    $arr[] = $row;
}

$t1 = microtime(true);
$mem = memory_get_usage(true);

echo 'Array of ' . number_format($L) . ' arrays.' . PHP_EOL;
echo 'Time taken: ' . number_format($t1 - $t0, 3) . 'ms.' . PHP_EOL;
echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB.' . PHP_EOL;

$arr = null;
gc_collect_cycles();

$mem = memory_get_usage(true);
echo PHP_EOL;
echo 'Array of 0 items.' . PHP_EOL;
echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB after gc_collect_cycles().' . PHP_EOL;
