<?php 

// Database Connection
$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
mysql_select_db(DB_DATABASE, $CM_MYSQL);

// File handling for templates

/*******************************************
	Function:	get_header
*******************************************/
function get_header($topBar,$pageTitle,$sectionTitle)
{
	if (file_exists(SITE_TEMPLATE_ROOT . '/header.php'))
	{
		include_once(SITE_TEMPLATE_ROOT . '/header.php');
		return;
	} else {
		echo "<h3 style=\"color:red\">Error: Missing the header template! Please check your settings.</h3>";
	};
};


/*******************************************
	Function:	get_footer
*******************************************/
function get_footer()
{
	if (file_exists(SITE_TEMPLATE_ROOT . '/footer.php'))
	{
		include_once(SITE_TEMPLATE_ROOT . '/footer.php');
		return;
	} else {
		echo "<h3 style=\"color:red\">Error: Missing the footer template! Please check your settings.</h3>";
	};
};


/*******************************************
	Function:	get_sidebar
*******************************************/
function get_sidebar()
{
	if (file_exists(SITE_TEMPLATE_ROOT . '/sidebar.php'))
	{
		include_once(SITE_TEMPLATE_ROOT . '/sidebar.php');
		return;
	} else {
		echo "<h3 style=\"color:red\">Error: Missing the sidebar template! Please check your settings.</h3>";
	};
};

/*******************************************
	Function:	get_summaries
*******************************************/
function get_summaries()
{
	if (file_exists(SITE_TEMPLATE_ROOT . '/summaries.php'))
	{
		include_once(SITE_TEMPLATE_ROOT . '/summaries.php');
		return;
	} else {
		echo "<h3 style=\"color:red\">Error: Missing the summaries template! Please check your settings.</h3>";
	};
};


/*******************************************
	Function:	cm_error
*******************************************/
function cm_error($msg)
{
	echo $msg;
	exit;
}

/*******************************************
	Function:	site_info
*******************************************/
function site_info($field)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($field != "id" && $field != "active_poll") {;
		$field = "site_" . $field;
	};
	$query_CM_Array = "SELECT $field AS myval FROM cm_settings;";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$myval = $row_CM_Array['myval'];
		return $myval;	
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
}

/*******************************************
	Function:	section_info
*******************************************/
function section_info($field,$sel=1)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($field != "id") {;
		$field = "section_" . $field;
	};
	$query_CM_Array = "SELECT $field AS myval FROM cm_sections";
	$query_CM_Array .= " WHERE id = '$sel';";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$myval = $row_CM_Array['myval'];
		return $myval;	
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
}

/*******************************************
	Function:	issue_info
*******************************************/
function issue_info($field,$sel=1)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($field != "id") {;
		$field = "issue_" . $field;
	};
	$query_CM_Array = "SELECT $field AS myval FROM cm_issues";
	$query_CM_Array .= " WHERE id = '$sel';";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$myval = $row_CM_Array['myval'];
		return $myval;	
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
}


/*******************************************
	Function:	section_list
*******************************************/
function section_list($disp='list', $sel=1)
{;
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_sections ORDER BY section_priority ASC;";	
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);	
	do {;	
		$id = $row_CM_Array['id'];
		$section_name = $row_CM_Array['section_name'];
		$section_url = $row_CM_Array['section_url'];		

		if ($disp == "list") {;
		echo "\t<li><a href=\"$section_url\">" . htmlentities($section_name) . "</a></li>\n";
		};		
		// If called from menu
		if ($disp == "menu") {;
			echo "\t<option value=\"$id\"";
			if ($sel == $id) {;
				echo " selected";;	
			};
			echo ">" . htmlentities($section_name) . "</option>\n";
		};		
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	current_issue
*******************************************/
function current_issue($format)
{;	
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($format != "id") {;
		$format = "issue_" . $format;
	};
	if ($format == "issue_date") {;
		$query_CM_Array = "SELECT DATE_FORMAT(cm_issues.$format, '%b. %e, %Y') AS myvalue";	
	} else {;
		$query_CM_Array = "SELECT cm_issues.$format AS myvalue";
	};	
	$query_CM_Array .= " FROM cm_issues, cm_settings";
	$query_CM_Array .= " WHERE cm_settings.current_issue = cm_issues.id";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );	
	$value = $row_CM_Array['myvalue'];
	return $value;
};

/*******************************************
	Function:	next_issue
*******************************************/
function next_issue($format)
{;	
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($format != "id") {;
		$format = "issue_" . $format;
	};
	if ($format == "issue_date") {;
		$query_CM_Array = "SELECT DATE_FORMAT(cm_issues.$format, '%b. %e, %Y') AS myvalue";	
	} else {;
		$query_CM_Array = "SELECT cm_issues.$format AS myvalue";
	};	
	$query_CM_Array .= " FROM cm_issues, cm_settings";
	$query_CM_Array .= " WHERE cm_settings.next_issue = cm_issues.id";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );	
	$value = $row_CM_Array['myvalue'];
	return $value;
};

/*******************************************
	Function:	display_media
*******************************************/
function display_media($src,$type,$title='',$credit='',$caption='')
{;
	if ($type == "jpg" || $type == "png" || $type == "gif") {;
		echo "<p class=\"image\"><img src=\"$src\" alt=\"$type\" /></p>\n";
		if ($credit != "") {; echo "<p class=\"imageCredit\">$credit</p>\n"; };
		if ($caption != "") {; echo "<p class=\"imageCaption\">$caption</p>\n"; };
	};
	if ($type == "pdf" || $type == "doc") {;
		echo "<li class=\"mediaDocument\"><a href=\"$src\">$title ($type)</a></li>";
	};
	if ($type == "wav" || $type == "mp3") {;
		echo "<li class=\"mediaAudio\"><a href=\"$src\">$title ($type)</a></li>";
	};
	if ($type == "url") {;
		echo "<li class=\"mediaURL\"><a href=\"$src\">$title</a></li>";
	};
	if ($type == "swf") {;
		echo "<object type=\"application/x-shockwave-flash\" data=\"$src\">\n";
		echo "\t<param name=\"movie\" value=\"$src\" />\n";
		echo "</object>\n";
	};
};

/*******************************************
	Fuction: image_media
*******************************************/
function image_media($sel)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_media";	
	$query_CM_Array .= " WHERE article_id = '$sel'";
	$query_CM_Array .= " AND (media_type = 'jpg' OR media_type = 'png' OR media_type = 'gif' OR media_type = 'swf')";
	$query_CM_Array .= " ORDER BY id ASC;";
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	do {
		$id = $row_CM_Array['id'];
		$src = $row_CM_Array['media_src'];
		$type = $row_CM_Array['media_type'];
		$title = $row_CM_Array['media_title'];
		$credit = $row_CM_Array['media_credit'];
		$caption = $row_CM_Array['media_caption'];
		
		display_media($src,$type,$title,$credit,$caption);
		
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
}

/*******************************************
	Fuction: image_media_count
*******************************************/
function image_media_count($sel)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_media";	
	$query_CM_Array .= " WHERE article_id = '$sel'";
	$query_CM_Array .= " AND (media_type = 'jpg' OR media_type = 'png' OR media_type = 'gif' OR media_type = 'swf');";
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
	return $totalRows_CM_Array;
}

/*******************************************
	Fuction: related_media
*******************************************/
function related_media($sel)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_media";	
	$query_CM_Array .= " WHERE article_id = '$sel'";
	$query_CM_Array .= " AND (media_type = 'pdf' OR media_type = 'wav' OR media_type = 'url')";
	$query_CM_Array .= " ORDER BY id ASC;";
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	do {
		$id = $row_CM_Array['id'];
		$src = $row_CM_Array['media_src'];
		$type = $row_CM_Array['media_type'];
		$title = $row_CM_Array['media_title'];
		$credit = $row_CM_Array['media_credit'];
		$caption = $row_CM_Array['media_caption'];
		
		display_media($src,$type,$title,$credit,$caption);
		
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
}

/*******************************************
	Fuction: related_media_count
*******************************************/
function related_media_count($sel)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_media";	
	$query_CM_Array .= " WHERE article_id = '$sel'";
	$query_CM_Array .= " AND (media_type = 'pdf' OR media_type = 'wav' OR media_type = 'url');";
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
	return $totalRows_CM_Array;
}

/*******************************************
	Fuction: section_headlines
*******************************************/
function section_headlines($section=1,$issue,$limit=5)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_articles";	
	$query_CM_Array .= " WHERE section_id = '$section' AND issue_id = '$issue'";
	$query_CM_Array .= " ORDER BY article_priority ASC";
	$query_CM_Array .= " LIMIT 0,$limit;";
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	do {
		$id = $row_CM_Array['id'];
		$title = $row_CM_Array['article_title'];
		$link = site_info('url') . "/article.php?id=$id";
		
		echo "<li><a href=\"$link\" title=\"$title\">$title</a></li>\n";
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
}

/*******************************************
	Fuction: submit_story
*******************************************/
function submit_story($title,$text,$keyword,$author,$author_email,$author_classification,$author_major,$author_city,$author_telephone)
{
	// strip html tags
	$title = strip_tags($title);
	$text = strip_tags($text);
	$keyword = strip_tags($keyword);
	$author = strip_tags($author);
	$author_email = strip_tags($author_email);
	$author_classification = strip_tags($author_classification);
	$author_major = strip_tags($author_major);
	$author_city = strip_tags($author_city);
	$author_telephone = strip_tags($author_telephone);	
	$sent = date("Y-m-d h:i:s",time());
	$words = count_words($text);
	$issue_id = next_issue('id');
	$query = "INSERT INTO cm_submitted (submitted_title,submitted_text,submitted_keyword,submitted_author,submitted_author_email,submitted_author_classification,submitted_author_major,submitted_author_city,submitted_author_telephone,submitted_sent,submitted_words,issue_id) VALUES ('$title','$text','$keyword','$author','$author_email','$author_classification','$author_major','$author_city','$author_telephone','$sent','$words','$issue_id');";

	$stat = run_query($query);
	return $stat;

}

/*******************************************
	Fuction: create_search_query
*******************************************/
function create_search_query($string,$index,$sort_by,$sort_dir,$boolean)
{
	// Clean up fields, strip html tags
	$string = strip_tags($string);
	$sort_dir = strtoupper(strip_tags($sort_dir));
	
	// Set search mode
	if ($boolean == "true") { $mode = " IN BOOLEAN MODE"; };
	if ($index == "article") { $field = "article_text,article_title,article_subtitle"; };
	if ($index == "author") { $field = "article_author"; };
	if ($index == "keyword") { $field = "article_keywords"; };
	
	$query = "SELECT * FROM cm_articles WHERE MATCH ($field) AGAINST ('$string'$mode) ORDER BY $sort_by $sort_dir;";
	
	return $query;
}

/*******************************************
	Function:	get_ballot
*******************************************/
function get_ballot($field,$sel=0)
{
	$field = "poll_" . $field;

	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT $field AS myvalue FROM cm_poll_questions WHERE id = '$sel';";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	
	$myvalue = $row_CM_Array['myvalue'];
	
	return $myvalue;
};

/*******************************************
	Function:	cast_ballot
*******************************************/
function cast_ballot($sel)
{
	$poll_id = site_info('active_poll');
	$vote = $sel;
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$hostname = $_SERVER['HTTP_HOST'];
	
	// First, let's piss off ballot stuffers
	$runIt = poll_cleanup($poll_id);
	
	// Process the voting
	if (!empty($ip_address) && !empty($vote)) {
		$query = "INSERT INTO cm_poll_ballot (poll_id,ballot_response,ballot_ip_address,ballot_hostname) VALUES ('$poll_id','$vote','$ip_address','$hostname')";
		$stat = run_query($query);
		return $stat;
	} else {
		return 0;
	};
};

/*******************************************
	Function:	poll_results
*******************************************/
function poll_results($field,$sel=0)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);

	$query_CM_Array = "SELECT *";
	$query_CM_Array .= " FROM cm_poll_ballot";
	$query_CM_Array .= " WHERE poll_id = '$sel' AND ballot_response = '$field'";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
	return $totalRows_CM_Array;
	
}

/*******************************************
	Function:	poll_cleanup
*******************************************/
function poll_cleanup($sel)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);

	$query_CM_Array = "SELECT ballot_ip_address, COUNT(ballot_ip_address) AS ballots";
	$query_CM_Array .= " FROM cm_poll_ballot";
	$query_CM_Array .= " WHERE poll_id = '$sel'";
	$query_CM_Array .= " GROUP BY ballot_ip_address";
	$query_CM_Array .= " HAVING ballots > 1;";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
	if ($totalRows_CM_Array > 0) {;
		do {
			$ipad = $row_CM_Array['ballot_ip_address'];
			poll_delete_ballots($sel,$ipad);
		} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
	};
	
	return $totalRows_CM_Array;
	
};

/*******************************************
	Function:	poll_delete_ballots
*******************************************/
function poll_delete_ballots($sel,$ipad)
{
	$query = "DELETE";
	$query .= " FROM cm_poll_ballot";
	$query .= " WHERE ballot_ip_address = '$ipad'";
	$query .= " AND poll_id = '$sel'";
	$stat = run_query($query);
	return $stat;	
};

/*******************************************
	Function:	count_words
*******************************************/
function count_words($string)
{
	$word_count = 0;
	$string = eregi_replace(" +", " ", $string);
	$string = explode(" ", $string);
	
	while (list(, $word) = each ($string)) {
		if (eregi("[0-9A-Za-z?-??-??-?]", $word)) {
			$word_count++;
		}
	}
	return($word_count);
}

/*******************************************
	Function: run_query
*******************************************/
function run_query($query)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	// Database Query
	$query_CM_Array = $query;
	// Run Query
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	return $CM_Array;
};

/*******************************************
Function:	autop
*******************************************/
function autop($pee, $br = 1) {
	$pee = $pee . "\n"; // just to make things a little easier, pad the end
	$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
	$pee = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $pee); // Space things out a little
	$pee = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $pee); // Space things out a little
	$pee = preg_replace("/(\r\n|\r)/", "\n", $pee); // cross-platform newlines
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "\t<p>$1</p>\n", $pee); // make paragraphs, including one at the end
	$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee);
	if ($br) $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
	$pee = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br />!', "$1", $pee);
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $pee);
	$pee = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $pee);
	return $pee;
};

?>
