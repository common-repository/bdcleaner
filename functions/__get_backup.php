<?php
function bd_cleaner_table_dump( $table, $file ) {
	global $dbname;

	mysql_select_db( $dbname );

	$rows = mysql_query( "SELECT * FROM ".$table );
	$numfields = mysql_num_fields( $rows );

	$data = "DROP TABLE IF EXISTS `".$table."`;\n\n";
	$row2 = mysql_fetch_row( mysql_query( "SHOW CREATE TABLE ".$table ) );
	$data .= $row2[1].";\n\n";
	fwrite( $file, $data );

	for ( $i = 0; $i < $numfields; $i++ ) {
		while( $row = mysql_fetch_row( $rows ) ) {
			$data = "INSERT INTO `".$table."` VALUES (" ;
			for( $j = 0; $j < $numfields; $j++ ) {
				$row[$j] = addslashes( $row[$j] );
				$row[$j] = @preg_replace( "/\n(\s*\n)+/", PHP_EOL, $row[$j] );
				if ( isset( $row[$j] ) ) {
					$data .= '"'.$row[$j].'"';
				}
				else {
					$data .= '""';
				}

				if ( $j < ( $numfields-1 ) ) {
					$data .= ', ';
				}
			}
			$data .= ");\n";
			fwrite( $file, $data );
		}
	}
}
?>