#! /usr/bin/php
<?php
$defaults = [
	'm' => 2,
	'q' => 10,
	's' => 0,
	'c' => false,
	'h' => false,
	'n' => false,
];
function hasOption ($option, $onlyexists = false) {
	global $argv;
	global $defaults;
	foreach ($argv as $num=>$item) {
		if ($item == '-'.$option) {
			if ($onlyexists) {
				return true;
			}
			return $argv[$num + 1];
		}
	}
	return $defaults[$option];
}
function delay () {
	global $sleep;
	if ($sleep) {
		sleep(1);
	}
}
if (hasOption ('h', true)) {
	echo '
options:
	-q Quantity of voters (default 10);
	-m quantity of options (default 2);
	-s sleep between voters (default: 0);
	-c use Churov\'s power;
	-h this manual;
	-n no delays while counting voices;
';
	exit();
}
echo "!!!   vote simulator v 0.3 ready to work   !!! \n";
$quantity = hasOption('q');
$maximum = hasOption('m');
$sleep = !hasOption('n', true);
var_dump ($sleep);
for($i  = 1; $i <= $maximum; $i++) {
	$rating[$i] = rand(1, 10);
	@$totalrating +=  $rating[$i];
	//echo $i.'  '.$rating[$i].'  '.$totalrating."\n";
}
for($i = 0; $i < $quantity; $i++) {
	$votes[$i] = rand(1, $maximum);
	$rating_compare = 0;
	while($rating_compare < $rating[$votes[$i]]) {
		$rating_compare = rand(1, 10);
		$votes[$i] = rand(1, $maximum);
		//echo $rating_compare.'   '.$rating[$votes[$i]]."\n";
	}
	echo "voter #{$i} voted for {$votes[$i]}\n";
	sleep((int)hasOption('s'));
}
echo "all votes collected. Counting...\n";
delay();
for($i = 1; $i <= $maximum; $i++) {
	foreach ($votes as $vote) {
		if ($vote == $i) {
			@$result[$i]++;
		}
	}
}
echo "Counted. Preparing picture...\n";
delay();

foreach ($result as $option => $value) {
	if (hasOption('c', true)) {
		$max_percent = 146;
	}
	else {
		$max_percent = 100;
	}
	$percent = ($value / $quantity) * $max_percent;
	if (@$top < $value) {
		$top = $value;
	} 
	if ($option < 10) { echo 0; }
	echo $option;
	echo '  ';
	for($i = 0; $i <= $percent/2; $i++) {
		echo '=';
	}
	echo $percent."%\n";
}
echo 'The winner is...';
delay();
foreach ($result as $option => $value) {
	if ($value == $top) {
		@$winnerscount++;
				if ($winnerscount > 1) {
			echo ', ';
		}
		echo $option;
	}
}
echo "!!!\n"
?>
