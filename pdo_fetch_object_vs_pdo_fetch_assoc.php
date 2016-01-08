<?php

$dsn = 'sqlite::memory:';
$db = new PDO($dsn);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$createTableTest = <<< SQL
CREATE TABLE test (
	id INTEGER PRIMARY KEY,
	a VARCHAR(30),
	b REAL,
	c INTEGER,
	d DATETIME
);
SQL;

$insertTestRecord = <<<SQL2
INSERT INTO test VALUES(:id, :a, :b, :c, :d);
SQL2;

$selectTestRecords = <<<SQL3
SELECT *
FROM test
WHERE id BETWEEN 500000 AND 599999
SQL3;

prepareTestDb($db, $createTableTest, $insertTestRecord);

benchmarkFetchingAssocArrays($db, $selectTestRecords);
//benchmarkFetchingObjects($db, $selectTestRecords);

$db = null;// the end

function prepareTestDb($db, $createTableTest, $insertTestRecord)
{
	$t0 = microtime(true);
	
	$db->exec($createTableTest);
	$insertStmt = $db->prepare($insertTestRecord);

	$L = 1000000;
	$S = 'abcdefghijklmnopqrstuvwxyz';
	mt_srand();
	for($i = 1; $i <= $L; $i++) {
		$row = [
			':id' => $i,
			':a'  => str_shuffle($S),//string
			':b'  => mt_rand(0, $L) / mt_rand(1, $L),//float
			':c'  => mt_rand(-1000, 1000),//int
			':d'  => strtotime(mt_rand(-1000, 1000) . ' minutes')
		];
		$insertStmt->execute($row);
	}

	$t00 = microtime(true);
	$mem = memory_get_usage(true);
    
	echo 'Time taken: ' . number_format($t00 - $t0, 3) . 'ms to prepare ' . $L . ' records ' . PHP_EOL;
	echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB.' . PHP_EOL;
}


function benchmarkFetchingAssocArrays($db, $selectTestRecords)
{
	$t1 = microtime(true);
	$selectStmt = $db->prepare($selectTestRecords);
	$selectStmt->execute();
	$rows = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
	$count = count($rows);
	
	$t2 = microtime(true);
	$mem = memory_get_usage(true);

	echo 'Time taken: ' . number_format($t2 - $t1, 3) . 'ms to load ' . $count . ' arrays' . PHP_EOL;
	echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB.' . PHP_EOL;
}


class myRow
{
	public $id;
	public $a;
	public $b;
	public $c;
	public $d;
	
	function getData()
	{
		return 'id: ' . $this->id . ' a: ' . $this->a;
	}
}

function benchmarkFetchingObjects($db, $selectTestRecords)
{
	$t3 = microtime(true);
	$selectStmt = $db->prepare($selectTestRecords);
	$selectStmt->execute();
	
	$objects = $selectStmt->fetchAll(PDO::FETCH_CLASS, 'myRow');
	$count = count($objects);

	$t4 = microtime(true);
	$mem = memory_get_usage(true);

	echo 'Time taken: ' . number_format($t4 - $t3, 3) . 'ms to load ' . $count . ' objects' . PHP_EOL;
	echo 'Memory usage: ' . number_format($mem / 1024, 1) . 'KB.' . PHP_EOL;
	
	echo $objects[999]->getData() . PHP_EOL;
}

