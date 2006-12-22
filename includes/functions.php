<?php 

// Database Connection
$db = ADONewConnection('mysql');
$db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Start Template Engine
$smarty = new Smarty_PacerCMS;
$smarty->caching = USE_TEMPLATE_CACHE;

/*******************************************
	Function:	cm_error
*******************************************/
function cm_error($error_message)
{
	
	global $smarty;
	
	$smarty->assign("error_message", $error_message );
	$smarty->display("error.tpl");	
	exit;
}

/*******************************************
	Function:	site_info
*******************************************/
function site_info($field)
{

    global $db;

    // Column names not always prefixed
	if ($field != "id" && $field != "active_poll") {;
		$field = "site_" . $field;
	};
	
	$query = "SELECT $field AS myval FROM cm_settings;";

    $result = $db->Execute($query);
    
    return $result->Fields('myval');

}


/*******************************************
	Function:	section_info
*******************************************/
function section_info($field,$sel=1)
{
    global $db;
    
    // Column names not always prefixed
	if ($field != "id") {;
		$field = "section_" . $field;
	};
	
	$query = "SELECT $field AS myval FROM cm_sections";
	$query .= " WHERE id = '$sel';";

    $result = $db->Execute($query);
    
    return $result->Fields('myval');

}


/*******************************************
	Function:	issue_info
*******************************************/
function issue_info($field,$sel=1)
{
    
    global $db;

    // Column names not always prefixed
	if ($field != "id") {;
		$field = "issue_" . $field;
	};
	
	$query = "SELECT $field AS myval FROM cm_issues";
	$query .= " WHERE id = '$sel';";

    $result = $db->Execute($query);
    
    return $result->Fields('myval');
    
}


/*******************************************
	Function:	section_list
*******************************************/
function section_list($disp='array')
{

    global $db;
    
    // Database Query
    $query = "SELECT id, section_name, section_url ";
    $query .= " FROM cm_sections ORDER BY section_priority ASC;";	
       
    // Rather than echo, just return value arrays
    if ($disp == 'array')
    {
        $result = $db->Execute($query);        while ($array = $result->GetArray())
        {            $section_list = $array;
            return $section_list;        } 
    }  
}


/*******************************************
	Function:	current_issue
*******************************************/
function current_issue($format)
{;	
    global $db;

	if ($format != "id") {;
		$format = "issue_" . $format;
	};

	$query = "SELECT cm_issues.$format AS myval";
	$query .= " FROM cm_issues, cm_settings";
	$query .= " WHERE cm_settings.current_issue = cm_issues.id";
		
    $result = $db->Execute($query);
       
    return $result->Fields(myval);

};


/*******************************************
	Function:	next_issue
*******************************************/
function next_issue($format)
{
    global $db;

	if ($format != "id") {;
		$format = "issue_" . $format;
	};

	$query = "SELECT cm_issues.$format AS myval";
	$query .= " FROM cm_issues, cm_settings";
	$query .= " WHERE cm_settings.next_issue = cm_issues.id";
		
    $result = $db->Execute($query);
       
    return $result->Fields(myval);
}


/*******************************************
	Fuction: section_headlines
*******************************************/
function section_headlines($section=1,$issue,$disp='array')
{

    global $db;
    
    // Database Query
	$query = "SELECT id, article_title, article_summary, article_author, article_author_title ";
	$query .= " FROM cm_articles";	
	$query .= " WHERE section_id = '$section' AND issue_id = '$issue'";
	$query .= " ORDER BY article_priority ASC;";
       
    // Rather than echo, just return value arrays
    if ($disp == 'array')
    {
        $result = $db->Execute($query);        while ($array = $result->GetArray())
        {            $section_headlines = $array;
            return $section_headlines;        } 
    }  
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
    global $db;
	
    $result = $db->Execute($query);
    
    return $result;

};


/*******************************************
	Function:	autop
	Credit:
		Matthew Mullenweg of WordPress
		http://photomatt.net/scripts/autop/
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