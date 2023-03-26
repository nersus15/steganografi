<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nERROR: ",
	$heading,
	"\n\n",
	$message,
	"\n\n";
if(isset($redirect)){
	echo "<a href ='" , $redirect['link'] , "'>" , $redirect['text'] , '</a>';
}