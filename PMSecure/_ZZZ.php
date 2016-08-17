<?php
	//include('functions.php');

	// returns 1 if $t1 is greater than $t2 ($t1 comes after $t2)
	function compareTimes($t1, $t2) {
		$h1 = (int) substr($t1, 0, 2);
		$m1 = (int) substr($t1, 3, 2);
		$h2 = (int) substr($t2, 0, 2);
		$m2 = (int) substr($t2, 3, 2);

		//echo "h:" . $h1 . " m - " . $m1 . "\n" ;
		//echo "h:" . $h2 . " m - " . $m2 . "\n" ;

		$res;

		if( $h1 > $h2 )
			$res = 1;
		else if( $h1 == $h2 && $m1 > $m2 )
			$res = 1;
		else
			$res = 0;

		return $res;

	}

	function parseTime($date) {
		$len = strlen($date) - 3;
	 	$ora = substr($date, 0, $len);
		return $ora;
	}

	$tempo1 = "22:07:13";
	$tempo2 = "16:01:00";

	$zz = compareTimes($tempo1, $tempo2);
	echo $zz;
?>