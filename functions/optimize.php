<?php
function bd_cleaner_optimize() {
	global $wpdb;
	global $bdcleaner_conn;
	global $bd_cleaner_lang;

	$tbl_posts = $wpdb->prefix."posts";
	$tbl_comments = $wpdb->prefix."comments";
	$tbl_terms = $wpdb->prefix."terms";
	$tbl_term_taxonomy = $wpdb->prefix."term_taxonomy";
	$tbl_term_relationships = $wpdb->prefix."term_relationships";
	$tbl_options = $wpdb->prefix."options";
	$tbl_postmeta = $wpdb->prefix."postmeta";

	if ( $_GET['function'] == "" ) {
?>
<div class="wrap wsdplugin_content">
	<h2>BDcleaner - Optimize</h2>
	<div class="metabox-holder">
		<div style="width:70%; float: left;" class="postbox">
		<h3 class="hndle"><span>BD Optimizer</span></h3>
			<div class="inside">
				<p><?php echo $bd_cleaner_lang['o_descr']; ?></p>
				<div>
					<ul>
						<li><a href="?page=bdcleaner-index&function=encoding" onClick="return confirm('<?php echo $bd_cleaner_lang['o_alert_msg']; ?>')" ><?php echo $bd_cleaner_lang['o_clean_post']; ?></a> | <a target='_blank' href='http://digwp.com/2011/07/clean-up-weird-characters-in-database/' ><?php echo $bd_cleaner_lang['o_clean_read']; ?></a></li>
						<li><a href="?page=bdcleaner-index&function=deleteTags" onClick="return confirm('<?php echo $bd_cleaner_lang['o_alert_msg']; ?>')"><?php echo $bd_cleaner_lang['o_clean_tags']; ?></a>. | <a target='_blank' href='http://4rapiddev.com/tips-and-tricks/wordpress-delete-unused-post-tags-by-sql-command/'><?php echo $bd_cleaner_lang['o_clean_read']; ?></a></li>
						<li><a href="?page=bdcleaner-index&function=deleteFeed" onClick="return confirm('<?php echo $bd_cleaner_lang['o_alert_msg']; ?>')" ><?php echo$bd_cleaner_lang['o_clean_feed']; ?></a>. | <a target='_blank' href='http://wpengineer.com/2114/delete-all-feed-cache-via-sql-in-wordpress/'><?php echo $bd_cleaner_lang['o_clean_read']; ?></a></li>
						<li><a href="?page=bdcleaner-index&function=deleteUseragents" onClick="return confirm('<?php echo $bd_cleaner_lang['o_alert_msg']; ?>')" ><?php echo $bd_cleaner_lang['o_clean_uagent']; ?></a>. | <a target='_blank' href='http://www.binarynote.com/best-sql-queries-for-wordpress-administrator.html'><?php echo $bd_cleaner_lang['o_clean_read']; ?></a></li>
						<li><a href="?page=bdcleaner-index&function=deletePingback" onClick="return confirm('<?php echo $bd_cleaner_lang['o_alert_msg']; ?>')" ><?php echo $bd_cleaner_lang['o_clean_pingback']; ?></a>. | <a target='_blank' href='http://www.binarynote.com/best-sql-queries-for-wordpress-administrator.html'><?php echo $bd_cleaner_lang['o_clean_read']; ?></a></li>
					</ul>
				</div>
			</div>
		</div>

		<div style="width:28%; float: right;" class="postbox">
			<h3 class="hndle"><span><?php echo $bd_cleaner_lang['o_support']; ?></span></h3>
			<div class="inside">
				<ul>
					<li><a href="https://twitter.com/HackeaMesta" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Follow @HackeaMesta</a></li>
					<li><a href="https://twitter.com/eldragon87" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Follow @eldragon87</a></li>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_donations">
						<input type="hidden" name="business" value="contacto@xora.org">
						<input type="hidden" name="lc" value="MX">
						<input type="hidden" name="item_name" value="./xora - BDcleaner">
						<input type="hidden" name="no_note" value="0">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
						<input type="image" src="https://www.paypalobjects.com/es_XC/MX/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
						<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
					</form>
					<li>Donate (BTC): <strong>15K4qihL5DQmBThAP9uyM6ZtFj2rKsC18b</strong></li>
					<li><a target="_blank" href="http://rootprojects.com">www.rootprojects.com</a></li>
					<li><a target="_blank" href="http://xora.org/?p=contact"><?php echo $bd_cleaner_lang['bug']; ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php
	}
	if ( $_GET['function'] == "encoding" ) {
		$charset = array(
		"â€œ",
		"â€",
		"â€™",
		"â€˜",
		"â€“",
		"â€“",
		"â€¢",
		"â€¦"
		);

		?>
		<section><h3><a class='button-secondary' href="?page=bdcleaner-index">Go Back</a></h3></section>
		<p><?php echo $bd_cleaner_lang['o_clean_tables']; ?>: <strong><?php echo $tbl_posts; ?></strong> / <strong><?php echo $tbl_comments?></strong><br>
		<?php echo $bd_cleaner_lang['o_clean_query']; ?>:<br></p>
		<div class="postbox">
			<div class="inside">
		<?php
		foreach ( $charset as $value ) {
			$query_posts_ = "UPDATE ".$tbl_posts." SET post_content = REPLACE(post_content, '".$value."', '“');";
			$query_comments_ = "UPDATE ".$tbl_comments." SET comment_content = REPLACE(comment_content, '".$value."', '“');";

			$query_posts = $bdcleaner_conn->query( $query_posts_ );
			$query_comments = $bdcleaner_conn->query( $query_comments_ );
			echo $query_posts_."<br>";
			echo $query_comments_."<br>";
		}

		if ( $query_posts && $query_comments ) {
			echo "<p><a target='_blank' href='http://digwp.com/2011/07/clean-up-weird-characters-in-database/' >".$bd_cleaner_lang['o_clean_read']."</a></p>";
		}

		else {
			echo $bd_cleaner_lang['o_clean_error'];
		}
		?>
			</div>
		</div>
		<?php
	}

	if ( $_GET['function'] == "deleteTags" ) {
		$query_terms_ = "DELETE FROM ".$tbl_terms." WHERE term_id IN (SELECT term_id FROM ".$tbl_term_taxonomy." WHERE count = 0 );";
		$query_term_taxonomy_ = "DELETE FROM ".$tbl_term_taxonomy." WHERE term_id not IN (SELECT term_id FROM ".$tbl_terms.");";
		$query_term_relationships_ = "DELETE FROM ".$tbl_term_relationships." WHERE term_taxonomy_id not IN (SELECT term_taxonomy_id FROM ".$tbl_term_taxonomy.");";

		$query_terms = $bdcleaner_conn->query( $query_terms_ );
		$query_term_taxonomy = $bdcleaner_conn->query( $query_term_taxonomy_ );
		$query_term_relationships = $bdcleaner_conn->query( $query_term_relationships_ );

		if ( $query_terms && $query_term_taxonomy && $query_term_relationships ) {
			echo "
			<section><h3><a class='button-secondary' href='?page=bdcleaner-index'>Go Back</a></h3></section>
			<p>".$bd_cleaner_lang['o_clean_tables'].": <strong>".$tbl_terms."</strong> / <strong>".$tbl_term_taxonomy."</strong> / <strong>".$tbl_term_relationships."</strong><br>
			".$bd_cleaner_lang['o_clean_query'].":<br></p>";
			echo "<div class='postbox'>
			<div class='inside'>
			<ul>
				<li>".$query_terms_."</li>
				<li>".$query_term_taxonomy_."</li>
				<li>".$query_term_relationships_."</li>
			</ul>
			<a target='_blank' href='http://4rapiddev.com/tips-and-tricks/wordpress-delete-unused-post-tags-by-sql-command/'>".$bd_cleaner_lang['o_clean_read']."</a>
			</div>
			</div>";
		}

		else {
			echo $bd_cleaner_lang['o_clean_error'];
		}
	}

	if ( $_GET['function'] == "deleteFeed" ) {
		$query_feed_ = "DELETE FROM `".$tbl_options."` WHERE `option_name` LIKE ('_transient%_feed_%');";

		$query_feed = $bdcleaner_conn->query( $query_feed_ );

		if ( $query_feed ) {
			echo "<section><h3><a class='button-secondary' href='?page=bdcleaner-index'>Go Back</a></h3></section>
			<p>".$bd_cleaner_lang['o_clean_tables'].": <strong>".$tbl_options."</strong><br>
			".$bd_cleaner_lang['o_clean_query'].":<br></p>";
			echo "<div class='postbox'>
			<div class='inside'>
			".$query_feed_."
			<br>
				<a target='_blank' href='http://wpengineer.com/2114/delete-all-feed-cache-via-sql-in-wordpress/'>".$bd_cleaner_lang['o_clean_read']."</a>
			</div>
			</div>";
		}

		else {
			echo $bd_cleaner_lang['o_clean_error'];
		}
	}

	if ( $_GET['function'] == "deleteUseragents" ) {
		$query_useragent_ = "UPDATE ".$tbl_comments." SET comment_agent = '';";
		$query_useragent = $bdcleaner_conn->query( $query_useragent_ );

		if ( $query_useragent ) {
			echo "<section><h3><a class='button-secondary' href='?page=bdcleaner-index'>Go Back</a></h3></section>
			<p>".$bd_cleaner_lang['o_clean_tables'].": <strong>".$tbl_comments."</strong><br>
			".$bd_cleaner_lang['o_clean_query'].":<br></p>";
			echo "<div class='postbox'>
			<div class='inside'>
			".$query_useragent_."
			<br>
				<a target='_blank' href='http://www.binarynote.com/best-sql-queries-for-wordpress-administrator.html'>".$bd_cleaner_lang['o_clean_read']."</a>
			</div>
			</div>";
		}

		else {
			echo $bd_cleaner_lang['o_clean_error'];
		}
	}

	if ( $_GET['function'] == "deletePingback" ) {
		$query_pingback_ = "DELETE FROM ".$tbl_comments." WHERE comment_type='pingback';";
		$query_pingback = $bdcleaner_conn->query( $query_pingback_ );

		if ( $query_pingback ) {
			echo "<section><h3><a class='button-secondary' href='?page=bdcleaner-index'>Go Back</a></h3></section>
			<p>".$bd_cleaner_lang['o_clean_tables'].": <strong>".$tbl_comments."</strong><br>
			".$bd_cleaner_lang['o_clean_query'].":<br></p>";
			echo "<div class='postbox'>
			<div class='inside'>
			".$query_pingback_."
			<br>
				<a target='_blank' href='http://www.binarynote.com/best-sql-queries-for-wordpress-administrator.html'>".$bd_cleaner_lang['o_clean_read']."</a>
			</div>
			</div>";
		}

		else {
			echo $bd_cleaner_lang['o_clean_error'];
		}
	}
}
?>
