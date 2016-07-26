<?php
	//include('functions.php');

	function parseTime($date) {
		list ($giorno, $ora) = split(" ", $date);
		list ($ora, $min, $sec) = split(":", $ora);

		$result = $ora . "DIO" . $min;

		return $result;
	}

	$tempo = "26/07/2016 15:07:13";
	$buf = parseTime($tempo);

	echo $buf;
	
?>