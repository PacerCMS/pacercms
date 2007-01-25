<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "issue-edit";
$pmodule = "issue-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {
	$mode = $_GET["action"];
}

// Default value for 'volume' field
$volume = $_COOKIE['issue-browse-volume'];

// If action is edit, call edit function
if ($_GET['action'] == "edit") { 
	if ($_POST['id'] != "") {
		// Get posted data
		$issue_year = $_POST['issue-year'];
		$issue_month = $_POST['issue-month'];
		$issue_day = $_POST['issue-day'];
		$date = $issue_year . "-" . $issue_month . "-" . $issue_day;
		$volume = $_POST['volume'];
		$number = $_POST['number'];
		$circulation = $_POST['circulation'];
		$online_only = $_POST['online_only'];
		$id	= $_POST['id'];		
		// Run function
		$stat = cm_edit_issue($date,$volume,$number,$circulation,$online_only,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_issue' function.");
			exit;
		}
	} else {
		cm_error("Did not have a issue to load.");
		exit;
	}
}

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['volume'] != "") { 
	// Get posted data
	$issue_year = $_POST['issue-year'];
	$issue_month = $_POST['issue-month'];
	$issue_day = $_POST['issue-day'];
	$date = $issue_year . "-" . $issue_month . "-" . $issue_day;
	$volume = $_POST['volume'];
	$number = $_POST['number'];
	$circulation = $_POST['circulation'];
	$online_only = $_POST['online_only'];
	// Run function
	$stat = cm_add_issue($date,$volume,$number,$circulation,$online_only);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {
		cm_error("Error in 'cm_add_issue' function.");
		exit;
	}
}

if ($mode == "edit") {
	$id = $_GET["id"];
	$query = "SELECT *,";
	$query .= " DATE_FORMAT(issue_date, '%Y') AS issue_year,";
	$query .= " DATE_FORMAT(issue_date, '%m') AS issue_month,";
	$query .= " DATE_FORMAT(issue_date, '%d') AS issue_day";
	$query .= " FROM cm_issues WHERE id = '$id;'";
	$result = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
	$result_array  = mysql_fetch_assoc($result);
	$result_row_count = mysql_num_rows($result);	
	if ($result_row_count != 1) {
		cm_error("That issue number cannot be loaded.");
	}	
	$id = $result_array['id'];
	$issue_day =  $result_array['issue_day'];
	$issue_month = $result_array['issue_month'];
	$issue_year = $result_array['issue_year'];
	$volume = $result_array['issue_volume'];
	$number = $result_array['issue_number'];
	$circulation = $result_array['issue_circulation'];
	$online_only = $result_array['online_only'];
} // End database call if in edit mode.

if ($issue_day == "") { $issue_day = date('d'); }
if ($issue_month == "") { $issue_month = date('m'); }
if ($issue_year == "") { $issue_year = date('Y'); }


get_cm_header();

?>


<h2><a href="<?php echo "$pmodule.php"; ?>">Issue Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Issue Editor</legend>
  <div class="sidebar">
    <?
	// Build Calendar
	$d = getdate(time());
	if ($_GET['month'] == "") { $month = $d["mon"]; } else { $month = $_GET['month'];	}
	if ($_GET['year'] == "") {  $year = $d["year"]; } else { $year = $_GET['year']; }
	$cal = new Calendar;
	echo $cal->getMonthView($month, $year);
?>
  </div>
  <p>
    <label for="date">Publication Date</label>
    <br />
    <select name="issue-month" id="issue-month">
      <option value="01" <?php if ($issue_month == "01") { echo "selected"; } ?>>January</option>
      <option value="02" <?php if ($issue_month == "02") { echo "selected"; } ?>>February</option>
      <option value="03" <?php if ($issue_month == "03") { echo "selected"; } ?>>March</option>
      <option value="04" <?php if ($issue_month == "04") { echo "selected"; } ?>>April</option>
      <option value="05" <?php if ($issue_month == "05") { echo "selected"; } ?>>May</option>
      <option value="06" <?php if ($issue_month == "06") { echo "selected"; } ?>>June</option>
      <option value="07" <?php if ($issue_month == "07") { echo "selected"; } ?>>July</option>
      <option value="08" <?php if ($issue_month == "08") { echo "selected"; } ?>>August</option>
      <option value="09" <?php if ($issue_month == "09") { echo "selected"; } ?>>September</option>
      <option value="10" <?php if ($issue_month == "10") { echo "selected"; } ?>>October</option>
      <option value="11" <?php if ($issue_month == "11") { echo "selected"; } ?>>November</option>
      <option value="12" <?php if ($issue_month == "12") { echo "selected"; } ?>>December</option>
    </select>
    <select name="issue-day" id="issue-day">
      <option value="01" <?php if ($issue_day == "01") { echo "selected"; } ?>>1</option>
      <option value="02" <?php if ($issue_day == "02") { echo "selected"; } ?>>2</option>
      <option value="03" <?php if ($issue_day == "03") { echo "selected"; } ?>>3</option>
      <option value="04" <?php if ($issue_day == "04") { echo "selected"; } ?>>4</option>
      <option value="05" <?php if ($issue_day == "05") { echo "selected"; } ?>>5</option>
      <option value="06" <?php if ($issue_day == "06") { echo "selected"; } ?>>6</option>
      <option value="07" <?php if ($issue_day == "07") { echo "selected"; } ?>>7</option>
      <option value="08" <?php if ($issue_day == "08") { echo "selected"; } ?>>8</option>
      <option value="09" <?php if ($issue_day == "09") { echo "selected"; } ?>>9</option>
      <option value="10" <?php if ($issue_day == "10") { echo "selected"; } ?>>10</option>
      <option value="11" <?php if ($issue_day == "11") { echo "selected"; } ?>>11</option>
      <option value="12" <?php if ($issue_day == "12") { echo "selected"; } ?>>12</option>
      <option value="13" <?php if ($issue_day == "13") { echo "selected"; } ?>>13</option>
      <option value="14" <?php if ($issue_day == "14") { echo "selected"; } ?>>14</option>
      <option value="15" <?php if ($issue_day == "15") { echo "selected"; } ?>>15</option>
      <option value="16" <?php if ($issue_day == "17") { echo "selected"; } ?>>16</option>
      <option value="17" <?php if ($issue_day == "16") { echo "selected"; } ?>>17</option>
      <option value="18" <?php if ($issue_day == "18") { echo "selected"; } ?>>18</option>
      <option value="19" <?php if ($issue_day == "19") { echo "selected"; } ?>>19</option>
      <option value="20" <?php if ($issue_day == "20") { echo "selected"; } ?>>20</option>
      <option value="21" <?php if ($issue_day == "21") { echo "selected"; } ?>>21</option>
      <option value="22" <?php if ($issue_day == "22") { echo "selected"; } ?>>22</option>
      <option value="23" <?php if ($issue_day == "23") { echo "selected"; } ?>>23</option>
      <option value="24" <?php if ($issue_day == "24") { echo "selected"; } ?>>24</option>
      <option value="25" <?php if ($issue_day == "25") { echo "selected"; } ?>>25</option>
      <option value="26" <?php if ($issue_day == "26") { echo "selected"; } ?>>26</option>
      <option value="27" <?php if ($issue_day == "27") { echo "selected"; } ?>>27</option>
      <option value="28" <?php if ($issue_day == "28") { echo "selected"; } ?>>28</option>
      <option value="29" <?php if ($issue_day == "29") { echo "selected"; } ?>>29</option>
      <option value="30" <?php if ($issue_day == "30") { echo "selected"; } ?>>30</option>
      <option value="31" <?php if ($issue_day == "31") { echo "selected"; } ?>>31</option>
    </select>
    <select name="issue-year" id="issue-year">
      <option value="1900" <?php if ($issue_year == "1900") { echo "selected"; } ?>>1900</option>
      <option value="1901" <?php if ($issue_year == "1901") { echo "selected"; } ?>>1901</option>
      <option value="1902" <?php if ($issue_year == "1902") { echo "selected"; } ?>>1902</option>
      <option value="1903" <?php if ($issue_year == "1903") { echo "selected"; } ?>>1903</option>
      <option value="1904" <?php if ($issue_year == "1904") { echo "selected"; } ?>>1904</option>
      <option value="1905" <?php if ($issue_year == "1905") { echo "selected"; } ?>>1905</option>
      <option value="1906" <?php if ($issue_year == "1906") { echo "selected"; } ?>>1906</option>
      <option value="1907" <?php if ($issue_year == "1907") { echo "selected"; } ?>>1907</option>
      <option value="1908" <?php if ($issue_year == "1908") { echo "selected"; } ?>>1908</option>
      <option value="1909" <?php if ($issue_year == "1909") { echo "selected"; } ?>>1909</option>
      <option value="1910" <?php if ($issue_year == "1910") { echo "selected"; } ?>>1910</option>
      <option value="1911" <?php if ($issue_year == "1911") { echo "selected"; } ?>>1911</option>
      <option value="1912" <?php if ($issue_year == "1912") { echo "selected"; } ?>>1912</option>
      <option value="1913" <?php if ($issue_year == "1913") { echo "selected"; } ?>>1913</option>
      <option value="1914" <?php if ($issue_year == "1914") { echo "selected"; } ?>>1914</option>
      <option value="1915" <?php if ($issue_year == "1915") { echo "selected"; } ?>>1915</option>
      <option value="1916" <?php if ($issue_year == "1916") { echo "selected"; } ?>>1916</option>
      <option value="1917" <?php if ($issue_year == "1917") { echo "selected"; } ?>>1917</option>
      <option value="1918" <?php if ($issue_year == "1918") { echo "selected"; } ?>>1918</option>
      <option value="1919" <?php if ($issue_year == "1919") { echo "selected"; } ?>>1919</option>
      <option value="1920" <?php if ($issue_year == "1920") { echo "selected"; } ?>>1920</option>
      <option value="1921" <?php if ($issue_year == "1921") { echo "selected"; } ?>>1921</option>
      <option value="1922" <?php if ($issue_year == "1922") { echo "selected"; } ?>>1922</option>
      <option value="1923" <?php if ($issue_year == "1923") { echo "selected"; } ?>>1923</option>
      <option value="1924" <?php if ($issue_year == "1924") { echo "selected"; } ?>>1924</option>
      <option value="1925" <?php if ($issue_year == "1925") { echo "selected"; } ?>>1925</option>
      <option value="1926" <?php if ($issue_year == "1926") { echo "selected"; } ?>>1926</option>
      <option value="1927" <?php if ($issue_year == "1927") { echo "selected"; } ?>>1927</option>
      <option value="1928" <?php if ($issue_year == "1928") { echo "selected"; } ?>>1928</option>
      <option value="1929" <?php if ($issue_year == "1929") { echo "selected"; } ?>>1929</option>
      <option value="1930" <?php if ($issue_year == "1930") { echo "selected"; } ?>>1930</option>
      <option value="1931" <?php if ($issue_year == "1931") { echo "selected"; } ?>>1931</option>
      <option value="1932" <?php if ($issue_year == "1932") { echo "selected"; } ?>>1932</option>
      <option value="1933" <?php if ($issue_year == "1933") { echo "selected"; } ?>>1933</option>
      <option value="1934" <?php if ($issue_year == "1934") { echo "selected"; } ?>>1934</option>
      <option value="1935" <?php if ($issue_year == "1935") { echo "selected"; } ?>>1935</option>
      <option value="1936" <?php if ($issue_year == "1936") { echo "selected"; } ?>>1936</option>
      <option value="1937" <?php if ($issue_year == "1937") { echo "selected"; } ?>>1937</option>
      <option value="1938" <?php if ($issue_year == "1938") { echo "selected"; } ?>>1938</option>
      <option value="1939" <?php if ($issue_year == "1939") { echo "selected"; } ?>>1939</option>
      <option value="1940" <?php if ($issue_year == "1940") { echo "selected"; } ?>>1940</option>
      <option value="1941" <?php if ($issue_year == "1941") { echo "selected"; } ?>>1941</option>
      <option value="1942" <?php if ($issue_year == "1942") { echo "selected"; } ?>>1942</option>
      <option value="1943" <?php if ($issue_year == "1943") { echo "selected"; } ?>>1943</option>
      <option value="1944" <?php if ($issue_year == "1944") { echo "selected"; } ?>>1944</option>
      <option value="1945" <?php if ($issue_year == "1945") { echo "selected"; } ?>>1945</option>
      <option value="1946" <?php if ($issue_year == "1946") { echo "selected"; } ?>>1946</option>
      <option value="1947" <?php if ($issue_year == "1947") { echo "selected"; } ?>>1947</option>
      <option value="1948" <?php if ($issue_year == "1948") { echo "selected"; } ?>>1948</option>
      <option value="1949" <?php if ($issue_year == "1949") { echo "selected"; } ?>>1949</option>
      <option value="1950" <?php if ($issue_year == "1950") { echo "selected"; } ?>>1950</option>
      <option value="1951" <?php if ($issue_year == "1951") { echo "selected"; } ?>>1951</option>
      <option value="1952" <?php if ($issue_year == "1952") { echo "selected"; } ?>>1952</option>
      <option value="1953" <?php if ($issue_year == "1953") { echo "selected"; } ?>>1953</option>
      <option value="1954" <?php if ($issue_year == "1954") { echo "selected"; } ?>>1954</option>
      <option value="1955" <?php if ($issue_year == "1955") { echo "selected"; } ?>>1955</option>
      <option value="1956" <?php if ($issue_year == "1956") { echo "selected"; } ?>>1956</option>
      <option value="1957" <?php if ($issue_year == "1957") { echo "selected"; } ?>>1957</option>
      <option value="1958" <?php if ($issue_year == "1958") { echo "selected"; } ?>>1958</option>
      <option value="1959" <?php if ($issue_year == "1959") { echo "selected"; } ?>>1959</option>
      <option value="1960" <?php if ($issue_year == "1960") { echo "selected"; } ?>>1960</option>
      <option value="1961" <?php if ($issue_year == "1961") { echo "selected"; } ?>>1961</option>
      <option value="1962" <?php if ($issue_year == "1962") { echo "selected"; } ?>>1962</option>
      <option value="1963" <?php if ($issue_year == "1963") { echo "selected"; } ?>>1963</option>
      <option value="1964" <?php if ($issue_year == "1964") { echo "selected"; } ?>>1964</option>
      <option value="1965" <?php if ($issue_year == "1965") { echo "selected"; } ?>>1965</option>
      <option value="1966" <?php if ($issue_year == "1966") { echo "selected"; } ?>>1966</option>
      <option value="1967" <?php if ($issue_year == "1967") { echo "selected"; } ?>>1967</option>
      <option value="1968" <?php if ($issue_year == "1968") { echo "selected"; } ?>>1968</option>
      <option value="1969" <?php if ($issue_year == "1969") { echo "selected"; } ?>>1969</option>
      <option value="1970" <?php if ($issue_year == "1970") { echo "selected"; } ?>>1970</option>
      <option value="1971" <?php if ($issue_year == "1971") { echo "selected"; } ?>>1971</option>
      <option value="1972" <?php if ($issue_year == "1972") { echo "selected"; } ?>>1972</option>
      <option value="1973" <?php if ($issue_year == "1973") { echo "selected"; } ?>>1973</option>
      <option value="1974" <?php if ($issue_year == "1974") { echo "selected"; } ?>>1974</option>
      <option value="1975" <?php if ($issue_year == "1975") { echo "selected"; } ?>>1975</option>
      <option value="1976" <?php if ($issue_year == "1976") { echo "selected"; } ?>>1976</option>
      <option value="1977" <?php if ($issue_year == "1977") { echo "selected"; } ?>>1977</option>
      <option value="1978" <?php if ($issue_year == "1978") { echo "selected"; } ?>>1978</option>
      <option value="1979" <?php if ($issue_year == "1979") { echo "selected"; } ?>>1979</option>
      <option value="1980" <?php if ($issue_year == "1980") { echo "selected"; } ?>>1980</option>
      <option value="1981" <?php if ($issue_year == "1981") { echo "selected"; } ?>>1981</option>
      <option value="1982" <?php if ($issue_year == "1982") { echo "selected"; } ?>>1982</option>
      <option value="1983" <?php if ($issue_year == "1983") { echo "selected"; } ?>>1983</option>
      <option value="1984" <?php if ($issue_year == "1984") { echo "selected"; } ?>>1984</option>
      <option value="1985" <?php if ($issue_year == "1985") { echo "selected"; } ?>>1985</option>
      <option value="1986" <?php if ($issue_year == "1986") { echo "selected"; } ?>>1986</option>
      <option value="1987" <?php if ($issue_year == "1987") { echo "selected"; } ?>>1987</option>
      <option value="1988" <?php if ($issue_year == "1988") { echo "selected"; } ?>>1988</option>
      <option value="1989" <?php if ($issue_year == "1989") { echo "selected"; } ?>>1989</option>
      <option value="1990" <?php if ($issue_year == "1990") { echo "selected"; } ?>>1990</option>
      <option value="1991" <?php if ($issue_year == "1991") { echo "selected"; } ?>>1991</option>
      <option value="1992" <?php if ($issue_year == "1992") { echo "selected"; } ?>>1992</option>
      <option value="1993" <?php if ($issue_year == "1993") { echo "selected"; } ?>>1993</option>
      <option value="1994" <?php if ($issue_year == "1994") { echo "selected"; } ?>>1994</option>
      <option value="1995" <?php if ($issue_year == "1995") { echo "selected"; } ?>>1995</option>
      <option value="1996" <?php if ($issue_year == "1996") { echo "selected"; } ?>>1996</option>
      <option value="1997" <?php if ($issue_year == "1997") { echo "selected"; } ?>>1997</option>
      <option value="1998" <?php if ($issue_year == "1998") { echo "selected"; } ?>>1998</option>
      <option value="1999" <?php if ($issue_year == "1999") { echo "selected"; } ?>>1999</option>
      <option value="2000" <?php if ($issue_year == "2000") { echo "selected"; } ?>>2000</option>
      <option value="2001" <?php if ($issue_year == "2001") { echo "selected"; } ?>>2001</option>
      <option value="2002" <?php if ($issue_year == "2002") { echo "selected"; } ?>>2002</option>
      <option value="2003" <?php if ($issue_year == "2003") { echo "selected"; } ?>>2003</option>
      <option value="2004" <?php if ($issue_year == "2004") { echo "selected"; } ?>>2004</option>
      <option value="2005" <?php if ($issue_year == "2005") { echo "selected"; } ?>>2005</option>
      <option value="2006" <?php if ($issue_year == "2006") { echo "selected"; } ?>>2006</option>
      <option value="2007" <?php if ($issue_year == "2007") { echo "selected"; } ?>>2007</option>
      <option value="2008" <?php if ($issue_year == "2008") { echo "selected"; } ?>>2008</option>
      <option value="2009" <?php if ($issue_year == "2009") { echo "selected"; } ?>>2009</option>
      <option value="2010" <?php if ($issue_year == "2010") { echo "selected"; } ?>>2010</option>
      <option value="2011" <?php if ($issue_year == "2011") { echo "selected"; } ?>>2011</option>
      <option value="2012" <?php if ($issue_year == "2012") { echo "selected"; } ?>>2012</option>
      <option value="2013" <?php if ($issue_year == "2013") { echo "selected"; } ?>>2013</option>
      <option value="2014" <?php if ($issue_year == "2014") { echo "selected"; } ?>>2014</option>
      <option value="2015" <?php if ($issue_year == "2015") { echo "selected"; } ?>>2015</option>
      <option value="2016" <?php if ($issue_year == "2016") { echo "selected"; } ?>>2016</option>
      <option value="2017" <?php if ($issue_year == "2017") { echo "selected"; } ?>>2017</option>
      <option value="2018" <?php if ($issue_year == "2018") { echo "selected"; } ?>>2018</option>
      <option value="2019" <?php if ($issue_year == "2019") { echo "selected"; } ?>>2019</option>
      <option value="2020" <?php if ($issue_year == "2020") { echo "selected"; } ?>>2020</option>
      <option value="2021" <?php if ($issue_year == "2021") { echo "selected"; } ?>>2021</option>
      <option value="2022" <?php if ($issue_year == "2022") { echo "selected"; } ?>>2022</option>
      <option value="2023" <?php if ($issue_year == "2023") { echo "selected"; } ?>>2023</option>
      <option value="2024" <?php if ($issue_year == "2024") { echo "selected"; } ?>>2024</option>
      <option value="2025" <?php if ($issue_year == "2025") { echo "selected"; } ?>>2025</option>
      <option value="2026" <?php if ($issue_year == "2026") { echo "selected"; } ?>>2026</option>
      <option value="2027" <?php if ($issue_year == "2027") { echo "selected"; } ?>>2027</option>
      <option value="2028" <?php if ($issue_year == "2028") { echo "selected"; } ?>>2028</option>
    </select>
  </p>
  <p>
    <label for="volume">Volume</label>
    <br />
    <input type="text" name="volume" id="volume" value="<?php echo $volume; ?>" class="text" />
  </p>
  <p>
    <label for="number">Issue Number</label>
    <br />
    <input type="text" name="number" id="number" value="<?php echo $number; ?>" class="text" />
  </p>
  <p>
    <label for="circulation">Circulation</label>
    <br />
    <input type="text" name="circulation" id="circulation" value="<?php echo $circulation; ?>" class="text" />
  </p>
  <p>
    <label for="online_only">Online Only?</label>
    <br />
    <input type="radio" name="online_only" id="online_only_yes" value="1" class="radio" <?php if ($online_only == '1') { echo "checked"; } ?> />
    <label for="online_only_yes">Yes</label>
    <br />
    <input type="radio" name="online_only" id="online_only_no" value="0" class="radio" <?php if ($online_only == '0') { echo "checked"; } ?> />
    <label for="online_only_no">No</label>
    <br />
  </p>
  <p>
    <?php if ($mode == "new") { ?>
    <input type="submit" value="Add Issue" name="submit" id="submit" class="button" />
    <?php } if ($mode == "edit") { ?>
    <input type="submit" value="Update Issue" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
