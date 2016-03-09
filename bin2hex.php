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


const CHUNKSIZE = 1024;

$stream = STDIN;
stream_set_blocking($stream, 0);

while (!feof($stream)) {
	$r = array($stream);
	$w = null;
	$e = null;
	stream_select($r, $w, $e, 60);
	echo bin2hex(fread($stream, CHUNKSIZE));
}


// vim: set ts=4 noet ai
