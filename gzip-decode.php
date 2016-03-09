#!/usr/bin/env php
<?php
/**
	Part of PNGParse.php

	Copyright © 2016 All rights reserved.

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <https://www.gnu.org/licenses/>.

	@license GPL-3+
	@file
*/


const SIGNATURE = "\037\213";

const FHCRC = 0x2;
const FEXTRA = 0x4;
const FNAME = 0x8;
const FCOMMENT = 0x10;

$stream = STDIN;

if (SIGNATURE !== fread($stream, strlen(SIGNATURE))) {
	throw new Exception("Invalid gzip signature");
}

$type = fgetc($stream);

$adler32 = function ($data) {
	$a = 1;
	$b = 0;
	for ($i = 0; $i < strlen($data); $i++) {
		$a = ($a + ord($data[$i])) % 65521;
		$b = ($b + $a) % 65521;
	}
	return pack('n*', $b, $a);
};

while (!feof($stream)) {
	$flags = end(unpack('C', fgetc($stream)));
	fread($stream, 6);
	if (FEXTRA & $flags) {
		$len = end(unpack('n', fread($stream, 2)));
		@fread($stream, $len);
	}
	if (FNAME & $flags) {
		stream_get_line($stream, 65536, "\0");
	}
	if (FCOMMENT & $flags) {
		stream_get_line($stream, 65536, "\0");
	}
	if (FHCRC & $flags) {
		fread($stream, 2);
	}
	echo "\x78";
	$data = substr(stream_get_contents($stream), 0, -8);
	$adler32(gzinflate($data));
	break;
}


// vim: set ts=4 noet ai
