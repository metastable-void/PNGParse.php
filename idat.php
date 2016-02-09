#!/usr/bin/env php
<?php

const SIGNATURE = "\x89PNG\r\n\x1a\n";

$stream = STDIN;

if (SIGNATURE !== fread($stream, strlen(SIGNATURE))) {
	throw new Exception("Invalid PNG signature");
}

while (!feof($stream)) {
	$pos = ftell($stream);
	$length = end(unpack('N', fread($stream, 4)));
	$type = fread($stream, 4);
	$data = $length > 0 ? fread($stream, $length) : '';
	$crc32 = hash('crc32b', $type . $data, true);
	if (fread($stream, 4) !== $crc32) {
		throw new Exception("Invalid CRC32 checksum");
	}
	
	if ('IDAT' === $type) {
		echo $data;
		break;
	}
	
	if ('IEND' === $type) {
		break;
	}
	
}


// vim: set ts=4 noet ai
