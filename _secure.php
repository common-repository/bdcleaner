<?php
function bd_cleaner_security( $variable ) {
	global $bdcleaner_conn;
	$variable = strip_tags( $variable );
	$variable = stripslashes( $variable );
	$variable = htmlentities( $variable );
	$variable = $bdcleaner_conn->real_escape_string( $variable );

	return $variable;
}
?>