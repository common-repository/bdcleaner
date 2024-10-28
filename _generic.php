<?php
require_once( ABSPATH . 'wp-config.php' );

$bdcleaner_conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

// Validate connection
if ( mysqli_connect_errno() ) {
	printf( "BDcleaner could not connect to database: %s\n", mysqli_connect_error() );
	exit();
}

?>