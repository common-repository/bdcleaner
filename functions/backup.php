<?php
function bd_cleaner_backup() {
	global $bdcleaner_conn;
	global $wpdb;
	global $bd_cleaner_lang;
	require_once ( '__get_backup.php' );

	// Get Database
	if ( $_GET['bd'] != "" ) {
		$dbname = bd_cleaner_security( $_GET['bd'] );
	}
	elseif ( $_GET['bd'] == "" ) {
		$dbname = DB_NAME;
	}

	$action = bd_cleaner_security( $_GET['action'] );

	$backupfile = bd_cleaner_security( $_GET['backup'] );

	$backupdir = ABSPATH."wp-content/plugins/bdcleaner/backups/";
	
	// Show Databases
	$db = $bdcleaner_conn->query( "SHOW DATABASES;" );
	?>
	<form action="?page=bdcleaner-backup&bd=<?php echo $dbname; ?>&action=dumpDB" method="POST">
	<p><h2><?php echo $bd_cleaner_lang['b_head']; ?>: <strong><?php echo $dbname; ?></strong> <?php if ( $dbname == DB_NAME ) { echo "(".$bd_cleaner_lang['b_current'].")"; }?></h2></p>
	<p><input type="submit" class="button-primary" name="selectBD" value="<?php echo $bd_cleaner_lang['b_button']; ?>"></p>
	<p><h3><?php echo $bd_cleaner_lang['b_select_bd']; ?></h3></p>
	<p>
		<select name="bd" onchange="javascript:bdselect(this)">
			<option value=""></option>
			<?php
			while ( $dbs = $db->fetch_array() ) {
				echo "<option value='".$dbs[0]."'>".$dbs[0]."</option>\n";
			}
			?>
		</select>
	</p>
	</form>
	<?php
	if ( isset( $_POST['selectBD'] ) ) {
		// Database & action
		if ( $dbname != "" && $action == "dumpDB" ) {

			// Connect database
			if ( $mysqlHandle = mysql_connect( DB_HOST, DB_USER, DB_PASSWORD ) ) {
				$time = gmdate( "m-j-Y-h-i-s", time() );
		        $rand = rand( 123456, 9999999 );
		        // Backup name
				$backupname = $dbname."_".$time."_".$rand.".sql";

				// Open file
				if ( $write = fopen( $backupdir.$backupname, "w" ) ) {
					mysql_select_db( $dbname, $mysqlHandle );
					$query_id = mysql_query( "SHOW tables",$mysqlHandle );

					// Write Backup
					fwrite( $write, "--\n-- BDcleaner SQL Dump\n-- Database name: `".$dbname."`\n-- http://xora.org/bdcleaner.php\n-- contacto@xora.org\n--\n\n");
					fwrite( $write, "CREATE DATABASE IF NOT EXISTS `".$dbname."`;\n");
					fwrite( $write, "USE `".$dbname."`;\n\n");
					// Get data
					while ( $row = mysql_fetch_array( $query_id, MYSQL_NUM ) ) {
						fwrite( $write, "--\n-- Table structure for table `".$row[0]."`\n--\n\n" );
						bd_cleaner_table_dump( $row[0], $write );
						fwrite( $write, "\n\n-- --------------------------------------------------------\n\n" );
					}
					fwrite( $write,  "--\n-- `".$dbname."` Database dump completed!\n--");
					echo "<p>".$bd_cleaner_lang['b_complete'].": ".$backupname.".</p>";
					fclose( $write );
				}

				// Error to open Backup file
				else {
					echo "<p>".$bd_cleaner_lang['b_permission']."</p>\n";
					if ( chmod( $backupdir, 0777 ) ) {
						echo "<p>".$bd_cleaner_lang['b_permission_ok']."</p>\n";
					}
					else {
						echo "<p>".$bd_cleaner_lang['b_permission_no']."</p>\n";
					}
				}
			}
			else {
				echo "<p>".$bd_cleaner_lang['b_error_connect']."</p>";
			}
		}
	}

	// Show Backups
	if ( is_admin() ) {
	?>
	<div class="wrap wsdplugin_content">
		<div class="metabox-holder">
			<div style="width:95%; float: left;" class="postbox">
			<h3 class="hndle"><span><?php echo $bd_cleaner_lang['b_exists']; ?></span></h3>
			<div class="updated">
				<p><?php echo $bd_cleaner_lang['b_warning']; ?> <strong>(<?php echo DB_NAME; ?>)</strong>. Use: <a target="_blank" href="http://www.phpmyadmin.net/">phpMyAdmin</a>. <a target="_blank" href="http://xora.org/bdcleaner.php#bug"><?php echo $bd_cleaner_lang['o_clean_read']; ?></a> </p>
			</div>
				<div class="inside">
					<div>
						<table class="wp-list-table widefat" cellpadding="0" cellspacing="0">
							<tbody>
							<?php
							$carpeta = opendir( $backupdir );
							while ( $archivo = readdir( $carpeta ) ) {
								// If is folder exclude
								if ( is_file( $archivo ) ) {
									$backup_file .= "";
								}
								// Is a File
								else {
									$backup_file .= $archivo.",";
								}
							}
							$backup_file = str_ireplace( "index.php", "", $backup_file );
							$backup_file = str_ireplace( "..", "", $backup_file );

							$backup_files = explode( ",", $backup_file );
							foreach ( $backup_files as $file ) {
								if ( $file != "" && $file != "." ) {
									?>
								<tr class="alternate">
									<td class='id column-id'>
										<?php echo $file; ?>
									</td>
									<td class="id column-id">
										<a class="button-primary" href="<?php echo plugin_dir_url(__FILE__)."../backups/".$file; ?>"><?php echo $bd_cleaner_lang['b_download']; ?></a>  <a class="button-secondary" onClick="return confirm('<?php echo $bd_cleaner_lang['b_confirm_r']; ?>')" href="?page=bdcleaner-backup&backup=<?php echo $file; ?>&action=restoreBAK"><?php echo $bd_cleaner_lang['b_restore']; ?></a>  <a class="button-secondary" onClick="return confirm('<?php echo $bd_cleaner_lang['b_confirm_d']; ?>')" href="?page=bdcleaner-backup&backup=<?php echo $file; ?>&action=deleteBAK"><?php echo $bd_cleaner_lang['b_errase']; ?></a>
									</td>
								</tr>
									<?php
								}
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		// Restore Backup
		if ( $action == "restoreBAK" && $backupfile != "" && is_admin() ) {
			// Open Backup
			if ( $backup_restore = fopen( $backupdir.$backupfile, "r" ) ) {
				// Encode data
				$data_backup = base64_encode( fread( $backup_restore, filesize( $backupdir.$backupfile ) ) );
				// Explode query and decode
				$sentencia_backup = explode( ";", base64_decode( $data_backup ) );
				// Execute every Query
				for ( $i = 0; $i < ( count( $sentencia_backup )-1 );  $i++ ) { 
					$query_execute = $wpdb->query( $sentencia_backup[$i] );
				}
				if ( $query_execute ) {
			?>
			<div class="wrap wsdplugin_content">
				<div class="metabox-holder">
					<div style="width:50%; float: left;" class="postbox">
						<div class="inside">
						<?php echo $bd_cleaner_lang['b_r_success']." ".$backupfile; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
				}
				else {
					echo $bd_cleaner_lang['b_r_error'];
				}
			}
			else {
				echo $bd_cleaner_lang['b_error_open'];
			}
		}

		// Delete Backup
		if ( $action == "deleteBAK" && $backupfile != ""  ) {
			// If exist Backup
			if ( file_exists( $backupdir.$backupfile ) ) {
				if ( unlink( $backupdir.$backupfile ) ) {
			?>
			<div class="wrap wsdplugin_content">
				<div class="metabox-holder">
					<div style="width:50%; float: left;" class="postbox">
						<div class="inside">
						<?php echo $bd_cleaner_lang['b_d_success']; ?><strong><?php echo $backupfile; ?></strong>
						</div>
					</div>
				</div>
			</div>
			<?php
				}
				else {
					echo $bd_cleaner_lang['b_d_error'];
				}
			}
			else {
				echo $bd_cleaner_lang['b_backup_notfound'];
			}
		}
	}
}
?>