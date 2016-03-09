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
