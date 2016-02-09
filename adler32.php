#!/usr/bin/env php
<?php


$stream = STDIN;

$a = 1;
$b = 0;
while (!feof($stream)) {
	$octet = fgetc($stream);
	if (strlen($octet) != 1) {
		break;
	}
	$a = ($a + ord($octet)) % 65521;
	$b = ($b + $a) % 65521;
}
//$n = $b << 16 | $a;
//fwrite(STDERR, "$a, $b, $n\n");

//echo pack('N', $n);
echo pack('n*', $b, $a);


// vim: set ts=4 noet ai
