<?php

/* Anflex GA utilities
	Functions for add_action
	Debug functions
*/

/* Debug functions */

$anflex_debug=false;

function anflex_msg($msg){
	if($anflex_debug){
		global $anflex_msg;
		$anflex_msg .= "$msg\n";
	}
}

function anflex_pmsg(){
	if($anflex_debug){
		global $anflex_msg;
		echo "<!--anflex messages:\n$anflex_msg\n-->";
	}
}

?>