<?php
/*
Plugin Name: BD Cleaner
Plugin URI: http://xora.org/bdcleaner.php
Description: BDcleaner allows backup, restore, explore, optimize, analyze and clean your database from plugins and custom searches.
Author: @HackeaMesta
Author URI: http://twitter.com/HackeaMesta
Version: 2.0
License: GPLv2
*/

require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once ( '_generic.php' );
require_once ( '_secure.php' );
require_once ( 'functions/analisis.php' );
require_once ( 'functions/optimize.php' );
require_once ( 'functions/backup.php' );

// Language
$lang = get_bloginfo('language');
if ( $lang == "es-ES" ) {
	require_once ( 'lang/es-ES.php' );
}
else {
	require_once ( 'lang/en-EN.php' );
}

function bd_cleaner_menu() {
	add_menu_page( 'Optimize Database', 'BD Cleaner', 'manage_options', 'bdcleaner-index', 'bd_cleaner_optimize', plugin_dir_url( __FILE__ ).'imagenes/bdcleaner.png' );
	add_submenu_page( 'bdcleaner-index', 'Scan and clean Database', 'Scan database', 'manage_options', 'bdcleaner-analisis', 'bd_cleaner_analisis' );
	add_submenu_page( 'bdcleaner-index', 'Backup your Database', 'Backup database', 'manage_options', 'bdcleaner-backup', 'bd_cleaner_backup' );
}

function bd_cleaner_styles_scripts() {
	?>
<script type="text/javascript">
	function showHide() {
		if ( document.getElementById("parametro_name").selectedIndex == 1 ) {
			document.getElementById("hidden_html").style.display = "";
		}
		else {
			document.getElementById("hidden_html").style.display = "none";
		}

		if ( document.getElementById("parametro_name").selectedIndex == 3 ) {
			document.getElementById("hidden_aioseo").style.display = "";
		}
		else {
			document.getElementById("hidden_aioseo").style.display = "none";
		}

		if ( document.getElementById("parametro_name").selectedIndex == 4 ) {
			document.getElementById("hidden_yoast").style.display = "";
		}
		else {
			document.getElementById("hidden_yoast").style.display = "none";
		}
	}

	function handleSelect( elm ) {
		window.location = "?page=bdcleaner-analisis&bd="+elm.value;
	}

	function bdselect( elm ) {
		window.location = "?page=bdcleaner-backup&bd="+elm.value+"&action=dumpDB";
	}
	
	!function ( d,s,id ) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if ( !d.getElementById(id)) {
			js=d.createElement(s);
			js.id=id;
			js.src="http://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js,fjs);
		}
	}
	(document,"script","twitter-wjs");
</script>

<style type="text/css">
	.bd_divider {
		color: #81BEF7;
	}
</style>
	<?php
}

if ( is_admin() ) {
	add_action( 'admin_menu','bd_cleaner_menu' );
	add_action( 'admin_head', 'bd_cleaner_styles_scripts' );
}
?>