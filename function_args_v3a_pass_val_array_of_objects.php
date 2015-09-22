<?php

$t0 = microtime(true);

function myProcess(array $array)
{
	foreach($array as $i => $row) {
        $row->a = strrev($row->a);
		$array[$i] = $row; //NORMALLY?!, no need to re-assign because objects in arrays have references
		//NOTE re-assignment costs more memory
    }

    return $array;
}

class myClass
{
    public $a;
    public $b;
    public $c;
    function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}

$arr = [];

$L = 1000000;
$S = 'abcdefghijklmnopqrstuvwxyz';
mt_srand();
for($i = 1; $i <= $L; $i++) {
    $row = new myClass(
        str_shuffle($S),//string
        mt_rand(0, $L) / mt_rand(1, $L),//float
        mt_rand(-1000, 1000)
    );
    $arr[] = $row;
}

echo '1 object 0 -> a: ' . ($arr[0]->a) . PHP_EOL;
$arr2 = myProcess($arr);
echo '1 object 0 -> a: ' . ($arr[0]->a) . PHP_EOL;
echo '2 object 0 -> a: ' . ($arr2[0]->a) . PHP_EOL;

$t1 = microtime(true);
$mem = memory_get_usage(true);

echo 'Array of ' . number_format($L) . ' custom objects.' . PHP_EOL;
echo 'Time taken: ' . number_format($t1 - $t0, 3) . 'ms.' . PHP_EOL;
echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB.' . PHP_EOL;


