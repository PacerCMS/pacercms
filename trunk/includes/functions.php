<?php 

// Database Connection
$db = ADONewConnection('mysql');
$db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Start Template Engine
$smarty = new Smarty_PacerCMS;
$smarty->caching = USE_TEMPLATE_CACHE;

/*
* Prints an error message
*
* This is the generic error handler for database or user input validation. If Smarty
* is available, it will use the error.tpl template to format the message.
* 
* @param    string  Error message to output
* @return   null    Function stops all processing
*/

function cm_error($error_message)
{
	global $smarty;
	
	if (!empty($smarty)) {
    	// Display the error template
    	$smarty->assign("error_message", $error_message );
    	$smarty->display("error.tpl");
	} else {
	   // If Smarty isn't configured
	   echo "<h2>Error: $error_message</h2>";
	}
	exit;
}

/*
* Returns values from settings table
*
* Used to grab current settings such as the site title, telephone number, etc.
*
* @todo     Let's not query the database every time for this info
* @param    string  Column name (less 'site_' prefix) of desired information; default 'url'
* @return   string  Value stored in database
*/
function site_info($field='url')
{
    global $db;

    // Column names not always prefixed
	if ($field != "id" && $field != "active_poll" && $field != "database_version") {;
		$field = "site_" . $field;
	};
	
	$query = "SELECT $field AS myval FROM cm_settings;";

    $result = $db->Execute($query);
    
    if (empty($result))
    {
        cm_error("Database connection failed");
    } else {    
        return $result->Fields('myval');
    }

}


/*
* Returns values from sections table
*
* Used to grab current settings such as editor name, URL, etc.
*
* @todo     Let's not query the database every time for this info
* @param    string  Column name (less 'section_' prefix) of desired information
* @param    int     The ID number of the selected section; default 1
* @return   string  Value stored in database
*/
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
    
    if (empty($result))
    {
        cm_error("Database connection failed");
    } else {    
        return $result->Fields('myval');
    }

}


/*
* Returns values from sections table
*
* Used to grab current settings such as editor name, URL, etc.
*
* @todo     We should probably cache at least the current issue
* @param    string  Column name (less 'issue_' prefix) of desired information
* @param    int     The ID number of the selected issue; default 1
* @return   string  Value stored in database
*/
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
    
    if (empty($result))
    {
        cm_error("Database connection failed");
    } else {    
        return $result->Fields('myval');
    }
    
}


/*
* Returns array of sections
*
* Used to grab current settings such as editor name, URL, etc. for all sections
*
* @todo     Let's not query the database every time for this info
* @todo     Could this be part of section_info()? They query the same data
* @param    string  Format of output (currently only accepts 'array'); default 'array'
* @return   array   Associative array of all sections, keyed on ID
*/
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


/*
* Returns data from issues table for current issue
*
* Used to grab the date, valume, issue, etc. for current issue.
*
* @todo     Let's not query the database every time for this info
* @param    string  Column name (less 'issue_' prefix) of desired information; default 'date'
* @return   string  Value stored in database
*/
function current_issue($format='date')
{;	
    global $db;

	if ($format != "id") {;
		$format = "issue_" . $format;
	};

	$query = "SELECT cm_issues.$format AS myval";
	$query .= " FROM cm_issues, cm_settings";
	$query .= " WHERE cm_settings.current_issue = cm_issues.id";
		
    $result = $db->Execute($query);
       
    if (empty($result))
    {
        cm_error("Database connection failed");
    } else {    
        return $result->Fields('myval');
    }

};


/*
* Returns data from issues table for current issue
*
* Used to grab the date, valume, issue, etc. for next issue.
*
* @todo     Let's not query the database every time for this info
* @param    string  Column name (less 'issue_' prefix) of desired information; default 'date'
* @return   string  Value stored in database
*/
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
       
    if (empty($result))
    {
        cm_error("Database connection failed");
    } else {    
        return $result->Fields('myval');
    }
}


/*
* Returns array from a section/issue
*
* Used to grab title, summary, author, etc. in a particular section and issue 
*
* @todo     Let's not query the database every time for this info
* @param    int     Selected section; default 1
* @param    int     Selected issue
* @param    string  Format of output (currently only accepts 'array'); default 'array'
* @return   array   Associative array of section contents
*/
function section_headlines($section=1,$issue,$disp='array')
{
    global $db;
    
    // Database Query
	$query = "SELECT id, article_title, article_subtitle, article_summary, article_author, article_author_title ";
	$query .= " FROM cm_articles";	
	$query .= " WHERE section_id = '$section' AND issue_id = '$issue'";
	$query .= " ORDER BY article_priority ASC;";
       
    // Rather than echo, just return value arrays
    if ($disp == 'array')
    {
        $result = $db->Execute($query);        if (!empty($result)) {
            while ($array = $result->GetArray())
            {                $section_headlines = $array;
                return $section_headlines;            } 
        } else {
            // Return empty array
            return array();
        }
    }  
}


/*
* Returns ballot information for selected poll
*
* Used to generate the questions and response choices for a Web poll
*
* @param    string  Column name (less 'issue_' prefix) of desired information; default 'date'
* @param    int     Selected poll; default 0
* @return   string  Value stored in database
*/
function get_ballot($field,$sel=0)
{
    global $db;
    if ($field != 'article_id')
    {
	   $field = "poll_" . $field;
    }
    
	$query = "SELECT $field AS myval FROM cm_poll_questions WHERE id = '$sel';";
	
    $result = $db->Execute($query);
	
    if (empty($result))
    {
        // Return nothing
        return 0;
    } else {    
        return $result->Fields('myval');
    }    
};


/*
* Writes ballot to the database
*
* Used to cast the ballot for the current active poll; Runs poll_cleanup() operation before writing to the database
*
* @param    int     Selected poll option
* @return   string  On success, returns true
*/
function cast_ballot($sel)
{
	$poll_id = site_info('active_poll');
	$vote = $sel;
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$hostname = $_SERVER['HTTP_HOST'];
	
	// This removes duplicates
	$stat = poll_cleanup($poll_id);
	
	// Process the voting
	if (!empty($ip_address) && !empty($vote)) {
		$query = "INSERT INTO cm_poll_ballot (poll_id,ballot_response,ballot_ip_address,ballot_hostname) VALUES ('$poll_id','$vote','$ip_address','$hostname')";
		$stat = run_query($query);
		return $stat;
	} else {
		return 0;
	};
};


/*
* Returns count of responses for a particular answer/poll
*
* Used to return the count of votes for an answer in a poll
*
* @todo     We could probably return all results with a GROUP BY query
* @param    string  Selected answer
* @param    int     Selected poll, default 0
* @return   int     Count of responses
*/
function poll_results($field,$sel=0)
{
	global $db;

	$query = "SELECT count(id) AS vote_count ";
	$query .= " FROM cm_poll_ballot ";
	$query .= " WHERE poll_id = '$sel' AND ballot_response = '$field'";

    $result = $db->Execute($query);
       
    if (empty($result))
    {
        cm_error("Database connection failed");
    } else {    
        return $result->Fields('vote_count');
    }
	
}


/*
* Delete duplicate poll entries
*
* Removes duplicate entries for a poll by IP address
*
* @todo     Should we also do a look for a saved cookie value?
* @param    int     Selected poll
* @return   mixed   Returns false if no overvotes found; record handle if overvotes found
*/
function poll_cleanup($sel)
{
	global $db;

	$query = "SELECT ballot_ip_address, COUNT(ballot_ip_address) AS ballots";
	$query .= " FROM cm_poll_ballot";
	$query .= " WHERE poll_id = '$sel'";
	$query .= " GROUP BY ballot_ip_address";
	$query .= " HAVING ballots > 1;";
	
	$result = $db->Execute($query);
       
    if (empty($result))
    {
        // No overvotes
        return false;
    } else {    
        // Clean up overvotes
        $ipad = $result->Fields('ballot_ip_address');
		$stat = poll_delete_ballots($sel,$ipad);
		return $stat;
    }
	
};


/*
* Deletes ballots by poll/IP address
*
* Helps poll_cleanup() by deleting the offending IP address for a poll
*
* @param    int     Selected poll ID
* @param    string  IP address in 0.0.0.0 format
* @return   mixed   Returns record handle
*/
function poll_delete_ballots($sel,$ipad)
{
	$query = "DELETE";
	$query .= " FROM cm_poll_ballot";
	$query .= " WHERE ballot_ip_address = '$ipad'";
	$query .= " AND poll_id = '$sel'";
	$stat = run_query($query);
	return $stat;	
};


/*
* Count words in string
*
* Returns approximate count of words in a string
*
* @param    string  String to be counted
* @return   int     Count of words
*/
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


/*
* Runs a query
*
* Sends the query to the database layer
*
* @param    string  Query
* @return   mixed   Returns record handle
*/
function run_query($query)
{
    global $db;
	
    $result = $db->Execute($query);
    
    if (empty($result))
    {
        cm_error("Database connection failed or malformed SQL.");
    } else {    
        return $result;
    }

};


/*
* Prepares a string for database insertion
*
* Detects 'magic_quotes' setting and reacts accordingly
*
* @param    string  String to be add slashed
* @return   string  Add slashed string
*/
function prep_string($string){    if (get_magic_quotes_gpc() == 1)    {        return($string);    } else {        return(addslashes($string));    }}


/*
* Convert plain text to HTML
*
* Take a multi-line text string and parse it for ideal HTML output
*
* @param    string  Text to be parsed
* @param    boolean Flag to convert line breaks; 1 - true, 0 - false
* @return   string  Parsed text
*/
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
