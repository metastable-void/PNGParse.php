#!/usr/bin/env php
<?php
/**
	Part of PNGParse.php
	PNG chunk viewer in PHP, exports complete PNG chunks in JSON!

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

echo '[', PHP_EOL;
while (!feof($stream)) {
	$pos = ftell($stream);
	$length = fread($stream, 4);
	if (false === $length) {
		throw new Exception("I/O error");
	}
	
	$length = end(unpack('N', $length));
	$type = fread($stream, 4);
	if (false === $type) {
		throw new Exception("I/O error");
	}
	
	$origLength = $length;
	$data = '';
	while ($length > 0) {
		$read = fread($stream, $length);
		if (false === $read) {
			throw new Exception("I/O error");
		}
		$data .= $read;
		$length -= strlen ($read);
	}
	$crc32 = hash('crc32b', $type . $data, true);
	if (fread($stream, 4) !== $crc32) {
		throw new Exception("Invalid CRC32 checksum");
	}
	
	echo json_encode(array(
		'type' => $type
		, 'position' => $pos
		, 'length' => $origLength
		, 'data' => bin2hex($data)
	));
	
	if ('IEND' === $type) {
		echo PHP_EOL;
		$pos = ftell($stream);
		break;
	}
	
	echo ',', PHP_EOL;
}
echo ']', PHP_EOL;

fprintf(STDERR, "0x%x: End of file\n", $pos);

// vim: set ts=4 noet ai
