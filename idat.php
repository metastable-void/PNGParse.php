#!/usr/bin/env php
<?php
/**
	Part of PNGParse.php
	PNG IDAT extractor in PHP.

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
