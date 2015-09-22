<?php

$t0 = microtime(true);

function myProcess(array & $array)
{
    foreach($array as $i => $row) {
        $row['a'] = strrev($row['a']);
		$array[$i] = $row;// we have to re-assign the row
    }

    //return $array; //no need to return, because we are modifying the array given by reference
}

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

echo '1 item 0 -> a:' . ($arr[0]['a']) . PHP_EOL;
myProcess($arr);
echo '1 item 0 -> a:' . ($arr[0]['a']) . PHP_EOL;

$t1 = microtime(true);
$mem = memory_get_usage(true);

echo 'Array of ' . number_format($L) . ' arrays.' . PHP_EOL;
echo 'Time taken: ' . number_format($t1 - $t0, 3) . 'ms.' . PHP_EOL;
echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB.' . PHP_EOL;



