#!/usr/bin/env php
<?php

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
