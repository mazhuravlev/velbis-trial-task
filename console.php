<?php
require_once 'Converter.php';
$converter = new Converter();

try {
	$csvNorm = $converter->normalize($argv[1])->sortCallsByDate($argv[2]);	
} catch (Exception $e) {
	if ($e) {
		fwrite(STDERR, $e->getMessage());
	}
}

foreach($csvNorm as $key => $value) {
	$string = $key.' - '.$value.',';
	fwrite(STDOUT, $string."\n");	
}
