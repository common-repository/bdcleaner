<?php
function installPlugins() {
	// List Plugins
	$carpeta = opendir( ABSPATH."wp-content/plugins/" );
	while ( $archivo = readdir( $carpeta ) ) {
		// If is folder
		if ( is_dir( $archivo ) ) {
			$plugin .= $archivo.",";
		}
		// Is a File
		else {
			$plugin .= $archivo.",";
		}
	}

	$plugin = str_ireplace( ".php", "", $plugin );
	$plugin = str_ireplace( ".zip", "", $plugin );
	$plugin = str_ireplace( ".txt", "", $plugin );
	$plugin = str_ireplace( ".html", "", $plugin );
	$plugin = str_ireplace( "..", "", $plugin );
	$plugin = str_ireplace( ".", "", $plugin );
	$plugin = str_ireplace( "index,", "", $plugin );
	$plugin = strtolower( $plugin );

	return $plugin;
}

function getPlugins() {
	$plugins = array(
		"All In One Seo" => "",
		"WordPress SEO By YOAST" => "",
		"Disqus Comment System (disqus)" => "disqus",
		"Akismet (akismet)" => "akismet",
		"bbPress (bbpress)" => "bbpress",
		"BuddyPress (buddypress)" => "buddypress"
		);

	return $plugins;
}

function getAioSeo() {
	$plugins = array(
		"AIO Seo (aioseop)" => "aioseop",
		"AIO Seo (aiosp)" => "aiosp",
		"AIO Seo (Description)" => "_aioseop_description",
		"AIO Seo (Keywords)" => "_aioseop_keywords",
		"AIO Seo (Title)" => "_aioseop_title"
		);
	return $plugins;
}

function getSEObyYoast () {
	$plugins = array(
		"SEO By YOAST (yoast)" => "yoast",
		"SEO by YOAST (Meta Description)" => "_yoast_wpseo_metadesc",
		"SEO by YOAST (Meta Robots)" => "_yoast_wpseo_meta-robots",
		"SEO by YOAST (Sitemap)" => "_yoast_wpseo_sitemap",
		"SEO by YOAST (Canonical)" => "_yoast_wpseo_canonical",
		"SEO by YOAST (Redirect)" => "_yoast_wpseo_redirect",
		"SEO by YOAST (Focus)" => "_yoast_wpseo_focus"
		);
	return $plugins;
}
?>