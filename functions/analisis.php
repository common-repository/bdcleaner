<?php
function bd_cleaner_analisis() {
	global $bdcleaner_conn;
	global $bd_cleaner_security;
	global $wpdb;
	global $bd_cleaner_lang;
	require_once ( '__get_plugins.php' );

	function marcaTextos( $cadena, $parametro ) {
		if ( strlen( $cadena ) > 300 ) {
			$cadena = substr( $cadena, 0, 300 )."<strong>...</strong>";
		}

		$cadena = str_replace ( $parametro, '<span style="color: #2D97FE;">'.$parametro."</span>", $cadena );
		return $cadena;
	}

	// Get Database
	if ( $_GET['bd'] != "" ) {
		$base = bd_cleaner_security( $_GET['bd'] );
	}
	elseif ( $_GET['bd'] == "" ) {
		$base = DB_NAME;
	}
	
	// Show Databases
	$db = $bdcleaner_conn->query( "SHOW DATABASES;" );
	?>
	<p><h2><?php echo $bd_cleaner_lang['s_head']; ?> <strong><?php echo $base; ?></strong> <?php if ( $base == DB_NAME ) { echo "(".$bd_cleaner_lang['b_current'].")"; }?></h2></p>
	<p><h3><?php echo $bd_cleaner_lang['b_select_bd']; ?></h3></p>
	<form name="basesdedatos">
		<select onchange="javascript:handleSelect(this)">
			<option value=""></option>
			<?php
			while ( $dbs = $db->fetch_array() ) {
				echo "<option value='".$dbs[0]."'>".$dbs[0]."</option>\n";
			}
			?>
		</select>
	</form>
	<?php

	// Show Tables
	if ( empty( $_POST['tabla_nombre'] ) && $_GET['accion'] == "" ) {
	?>
	<p><h3><?php echo $bd_cleaner_lang['s_select_tables']; ?> (max 20).</h3></p>
	<form name="tablas" action="?page=bdcleaner-analisis&bd=<?php echo $base; ?>" method="POST">
	<table class="wp-list-table widefat" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column">
					<input type="checkbox" />
				</th>
				<th scope="col" id="tabla" class="manage-column column-id"><?php echo $bd_cleaner_lang['s_table']; ?></th>
				<th scope="col" id="tamano" class="manage-column column-id"><?php echo $bd_cleaner_lang['s_size']; ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
		$tablas = $bdcleaner_conn->query( 'SHOW TABLE STATUS FROM `'.$base.'`' );
		$tablas_ = $tablas->num_rows;

		if ( $tablas_ > 0 ) {
			while( $tabla = $tablas->fetch_assoc() ) {
				$tabla_size = $tabla['Data_length'] + $tabla['Index_length'];
				$tabla_size = $tabla_size / 1024;
				$tabla_size = sprintf( "%0.3f", $tabla_size );
				?>
				<tr class="alternate">
					<th scope="row" class="check-column">
						<input value="<?php echo $tabla['Name']; ?>" type="checkbox" name="tabla_nombre[]"/>
					</th>
					<td class='id column-id'>
						<div style="margin-top:1px;"><?php echo $tabla['Name']; ?></a></div>
					</td>
					<td class='id column-id'>
						<div style="margin-top:1px;"><?php echo $tabla_size; ?> KB.</div>
					</td>
				</tr>
				<?php
			}
		}
		elseif ( $tablas_ == 0 ) {
			echo "<p>No Tables Found.</p>";
		}
		?>
		</tbody>
	</table>
	<select id="parametro_name" name="parametro" onchange="showHide()">
		<option></option>
		<option value="" >Custom search...</option>
		<option class="bd_divider" disabled>--- Custom Plugins</option>
		<?php
		foreach ( getPlugins() as $nombre_plugin => $key ) {
			echo "<option value='".$key."'>".$nombre_plugin."</option>\n";
		}
		?>
		<option class="bd_divider" disabled>--- Installed Plugins</option>
		<?php
		$search_install = explode( ",", installPlugins() );

		foreach ( $search_install as $install_plugin ) {
			if ( $install_plugin != "" ) {
			echo "<option value='".$install_plugin."'>".$install_plugin."</option>\n";
			}
		}
		?>
	</select>
	<select id="hidden_aioseo" name="aioseo_" style="display:none;">
		<option></option>
		<?php
		foreach ( getAioSeo() as $aioseo => $aioseo_clave ) {
			echo "<option value='".$aioseo_clave."'>".$aioseo."</option>\n";
		}
		?>
	</select>
	<select id="hidden_yoast" name="yoast_" style="display:none;">
		<option></option>
		<?php
		foreach ( getSEObyYoast() as $yoast => $yoast_clave ) {
			echo "<option value='".$yoast_clave."'>".$yoast."</option>\n";
		}
		?>
	</select>
	<input id="hidden_html" style="display:none;" size="25" type="text" name="parametro_personalizado" placeholder="Custom parameter / word...">
	<label for="limite"><?php echo $bd_cleaner_lang['s_limit']; ?> </label><input type="text" name="limite" value="30" size="4" >
	<input type="submit" class="button-primary" value="<?php echo $bd_cleaner_lang['s_button']; ?>" name="enviar_tablas">
	</form>
	<?php
	}
	$limite = bd_cleaner_security( $_POST['limite'] );

	if ( $limite == "" ) {
		$limite = 0;
	}

	// Mostrar Errores
	if ( isset( $_POST['enviar_tablas'] ) && !is_numeric( $limite ) ) {
		echo $bd_cleaner_lang['s_error_num'];
	}

	if ( isset( $_POST['enviar_tablas'] ) && $_POST['tabla_nombre'] == "" ) {
		echo $bd_cleaner_lang['s_error_tables'];
	}

	// Parametro
	if ( isset( $_POST['enviar_tablas'] ) && $_POST['parametro'] != "" ) {
		$parametro = bd_cleaner_security( $_POST['parametro'] );
	}
	elseif ( isset( $_POST['enviar_tablas'] ) && $_POST['aioseo_'] != "" ) {
		$parametro = bd_cleaner_security( $_POST['aioseo_'] );
	}
	elseif ( isset( $_POST['enviar_tablas'] ) && $_POST['yoast_'] != "" ) {
		$parametro = bd_cleaner_security( $_POST['yoast_'] );
	}
	elseif ( isset( $_POST['enviar_tablas'] ) && $_POST['parametro'] == "" ) {
		if ( $_POST['parametro_personalizado'] != "" ) {
			$parametro = bd_cleaner_security( $_POST['parametro_personalizado'] );
		}
		elseif ( $_POST['parametro_personalizado'] == "" ) {
			echo $bd_cleaner_lang['s_error_parameter'];
		}
	}

	// All Ok
	if ( isset( $_POST['enviar_tablas'] ) && $_POST['tabla_nombre'] != "" && is_numeric( $_POST['limite'] ) && $parametro != "" ) {

		// Limit for Query
		if ( $limite > 0 ) {
			$_limite = " LIMIT 0 , ".$limite;
		}
		elseif ( $_POST['limite'] == 0 ) {
			$_limite = "";
		}

		$numero_tablas = count( $_POST['tabla_nombre'] );

		// Max 20 Tables
		if ( $numero_tablas > 20 ) {
			echo "<p>".$bd_cleaner_lang['s_error_limit']." <a class='button-secondary' href='?page=bdcleaner-analisis&bd=".$base."'>".$bd_cleaner_lang['go_back']."</a>.</p>\n";
		}

		elseif ( $numero_tablas <= 20 ) {
			echo "<p><a class='button-secondary' href='?page=bdcleaner-analisis&bd=".$base."'>".$bd_cleaner_lang['go_back']."</a></p>\n";

			foreach ( $_POST['tabla_nombre'] as $indice => $tabla_valor ) {
				$tabla_valor = bd_cleaner_security( $tabla_valor );

				// Get Columns
				$columnas = $bdcleaner_conn->query( "SHOW COLUMNS FROM `".$base."`.`".$tabla_valor."`;" );

				// Get query foreach columns & Tables
				$cad_or = "SELECT * FROM  `".$base."`.`".$tabla_valor."` WHERE ";
				$nombre_columna = array();

				// WHERE column1 LIKE "%%" OR columna2 LIKE "%%"...
				while ( $fila = $columnas->fetch_array() ) {
					// Get array for columns:
					$nombre_columna[] = $fila[0];
					$cad_or .= " `".$tabla_valor."`.`".$fila[0]."` LIKE '%".$parametro."%' OR ";
				}

				// Clean Query & execute
				$cad_or = substr( $cad_or, 0,-3 );
				$query_master = $cad_or.$_limite.";";
				$execute = $wpdb->get_results( $query_master );

				if ( $wpdb->num_rows > 0 ) {
					echo "
					<form action='?page=bdcleaner-analisis&bd=".$base."&accion=delete' method='POST'>
					<table class='wp-list-table widefat' cellpadding='0' cellspacing='0'>
					<thead>					
						<tr class='alternate'>
							".$bd_cleaner_lang['s_table'].": <strong>".$tabla_valor."</strong>
						</tr>
						<tr class='alternate'>
							<th scope='col' id='cb' class='manage-column column-cb check-column '>
								<input type='checkbox' />
							</th>";
					// Show columns
					for( $j = 0; $j < count( $nombre_columna ); $j++ ) {
						echo "<th><strong>".$nombre_columna[$j]."</strong></th>";
					}
					echo "
						</tr>
					</thead>
					</tbody>";
					// Show data
					for ( $w = 0; $w < count( $execute ); $w++ ) {
						echo "
						<tr class='alternate'>
							<th scope='row' class='check-column'>
								<input value='".$execute[$w]->$nombre_columna[0]."' type='checkbox' name='data_id[]' />
							</th>\n";
						for ( $r = 0; $r < count( $nombre_columna ); $r++ ) {
							echo '<td class="id column-id">'.marcaTextos( $execute[ $w ]->$nombre_columna[ $r ], $parametro )."</td>\n";
						}
						echo "</tr>\n";
					}
					echo "<tr>
							<td class='id column-id'>
								<input type='hidden' value='".$tabla_valor."' name='tbl_delete' />
								<input type='hidden' value='".$nombre_columna[0]."' name='col_delete' />";

					?>
								<input type='submit' class='button-primary' value='<?php echo $bd_cleaner_lang['b_errase']; ?>' name='clean_data' onClick="return confirm('<?php echo $bd_cleaner_lang['s_alert_delete']; ?>')" >
							</td>
						</tr>
					</tbody>
					</table>
					</form>
					<br>
					<?php
				}
				else {
					echo "<p>".$bd_cleaner_lang['s_no_results']." <strong>".$parametro."</strong> ".$bd_cleaner_lang['s_on']." <strong>".$tabla_valor."</strong>.</p>";
				}
			}
		}
	}

	// Elimina registros
	if ( isset( $_POST['clean_data'] ) && $_GET['accion'] == "delete" ) {
		$tbl = bd_cleaner_security( $_POST['tbl_delete'] );
		$col = bd_cleaner_security( $_POST['col_delete'] );

		foreach ( $_POST['data_id'] as $id_delete ) {
			$id_delete = bd_cleaner_security( $id_delete );

			$query_delete = $bdcleaner_conn->query( "DELETE FROM `".$base."`.`".$tbl."` WHERE `".$base."`.`".$tbl."`.`".$col."` =  ".$id_delete );
			if ( $query_delete ) {
				$delete_get = TRUE;
			}
			else {
				$delete_get = FALSE;
			}
		}

		if ( $delete_get == TRUE ) {
			?>
			<p><a class='button-secondary' href='?page=bdcleaner-analisis&bd=<?php echo $base; ?>'><?php echo $bd_cleaner_lang['go_back']; ?></a></p>
			<p><?php echo count( $_POST['data_id'] )." ".$bd_cleaner_lang['s_delete_tables']; ?> <strong><?php echo $tbl; ?></strong></p>
			<?php
		}
		else {
			?>
			<p><a class='button-secondary' href='?page=bdcleaner-analisis&bd=<?php echo $base; ?>'><?php echo $bd_cleaner_lang['go_back']; ?></a></p>
			<p><strong>Error</strong> 0 <?php echo $bd_cleaner_lang['s_delete_tables']; ?> <strong><?php echo $tbl; ?></strong></p>
			<?php
		}
	}
}
?>