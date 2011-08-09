<?php

/**************************************************************
"Learning with Texts" (LWT) is released into the Public Domain.
This applies worldwide.
In case this is not legally possible, any entity is granted the
right to use this work for any purpose, without any conditions, 
unless such conditions are required by law.
***************************************************************/

// -------------------------------------------------------------

function get_version() {
	global $debug;
	return '1.0.3 (August 09 2011)' . 
	($debug ? ' <span class="red">DEBUG</span>' : '');
}

// -------------------------------------------------------------

function get_version_number() {
	$r = 'v';
	$v = get_version();
	$pos = strpos($v,' ',0);
	if ($pos === false) die ('wrong version:'. $v);
	$vn = preg_split ("/[.]/", substr($v,0,$pos));
	if (count($vn) < 3) die ('wrong version:'. $v);
	for ($i=0; $i<3; $i++) $r .= substr('000' . $vn[$i],-3);
	return $r;  // 'vxxxyyyzzz' wenn version = x.y.z
}

// -------------------------------------------------------------

function framesetheader($title) {
	@header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
	@header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	@header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
	@header( 'Pragma: no-cache' );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- ***********************************************************
"Learning with Texts" (LWT) is released into the Public Domain.
This applies worldwide.
In case this is not legally possible, any entity is granted the
right to use this work for any purpose, without any conditions, 
unless such conditions are required by law.
************************************************************ -->

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Learning with Texts :: <?php echo tohtml($title); ?></title>
</head>
<?php
}

// -------------------------------------------------------------

function pagestart_nobody($titeltext, $addcss='') {
	global $debug;
	@header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
	@header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	@header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
	@header( 'Pragma: no-cache' );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- ***********************************************************
"Learning with Texts" (LWT) is released into the Public Domain.
This applies worldwide.
In case this is not legally possible, any entity is granted the
right to use this work for any purpose, without any conditions, 
unless such conditions are required by law.
************************************************************ -->

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<style type="text/css">
  	@import url(css/styles.css);
  	<?php echo $addcss; ?>
	</style>
	<script type="text/javascript" src="js/jquery.js"><!-- jQuery © John Resig ** http://www.jquery.com --></script>
	<script type="text/javascript" src="js/sorttable/sorttable.js"><!-- sorttable © Stuart Langridge ** http://www.kryogenix.org/code/browser/sorttable/ --></script>
	<script type="text/javascript" src="js/overlib/overlib_mini.js"><!-- overLIB © Erik Bosrup ** http://www.bosrup.com/web/overlib/ --></script>
	<script type="text/javascript">
	//<![CDATA[
	<?php echo "var STATUSES = " . json_encode(get_statuses()) . ";\n"; ?>
	//]]>
	</script>
	<script type="text/javascript" src="js/pgm.js"></script>
	<script type="text/javascript" src="js/jq_pgm.js"></script>
	<title>Learning with Texts :: <?php echo $titeltext; ?></title>
</head>
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php
} 

// -------------------------------------------------------------

function pagestart($titeltext,$close) {
	global $debug;
	pagestart_nobody($titeltext);
	echo '<h4>';
	if ($close) echo '<a href="index.php" target="_top">';
	echo '<img class="lwtlogo" src="img/lwt_icon.png" alt="Logo" />Learning with Texts';
	if ($close) {
		echo '</a>&nbsp; | &nbsp;';
		quickMenu();
	}
	echo '</h4><h3>' . $titeltext . ($debug ? ' <span class="red">DEBUG</span>' : '') . '</h3>';
	echo "<p>&nbsp;</p>";
} 

// -------------------------------------------------------------

function pageend() {
	global $debug;
	if ($debug) showRequest();
?></body></html><?php
} 

// -------------------------------------------------------------

function selectmediapath($f) {
	$r = '<br /> or choose a file in .../' . basename(getcwd()) . '/media:<br /><select name="Dir" onchange="{val=this.form.Dir.options[this.form.Dir.selectedIndex].value; if (val != \'\') this.form.' . $f . '.value = val; this.form.Dir.value=\'\';}">';
	$r .= '<option value="">[Choose...]</option>';
	$r .= selectmediapathoptions('media');
	$r .= '</select>';
	return $r;
}

// -------------------------------------------------------------

function selectmediapathoptions($dir) {
	$mediadir = scandir($dir);
	$r = '<option disabled="disabled">-- Directory: ' . tohtml($dir) . ' --</option>';
	foreach ($mediadir as $entry) {
		if (substr($entry,0,1) != '.') {
			if (! is_dir($dir . '/' . $entry)) 
				$r .= '<option value="' . tohtml($dir . '/' . $entry) . '">' . tohtml($dir . '/' . $entry) . '</option>';
		}
	}
	foreach ($mediadir as $entry) {
		if (substr($entry,0,1) != '.') {
			if (is_dir($dir . '/' . $entry)) $r .= selectmediapathoptions($dir . '/' . $entry);
		}
	}
	return $r;
}

// -------------------------------------------------------------

function quickMenu() {
?><select id="quickmenu" onchange="{var qm = document.getElementById('quickmenu'); var val=qm.options[qm.selectedIndex].value; qm.selectedIndex=0; if (val != '') { if (val == 'INFO') {top.location.href='info.htm';} else {top.location.href = val + '.php';}}}">
<option value="" selected="selected">[Menu]</option>
<option disabled="disabled">--------</option>
<option value="index">Home</option>
<option disabled="disabled">--------</option>
<option value="edit_texts">Texts</option>
<option value="edit_archivedtexts">Archive</option>
<option disabled="disabled">--------</option>
<option value="edit_languages">Languages</option>
<option value="edit_words">Terms</option>
<option value="statistics">Statistics</option>
<option disabled="disabled">--------</option>
<option value="check_text">Text Check</option>
<option value="upload_words">Import</option>
<option value="backup_restore">Backup</option>
<option disabled="disabled">--------</option>
<option value="settings">Settings</option>
<option disabled="disabled">--------</option>
<option value="INFO">Help</option>
</select><?php
}

// -------------------------------------------------------------

function error_message_with_hide($msg,$noback) {
	if (trim($msg) == '') return '';
	if (substr($msg,0,5) == "Error" )
		return '<p class="red">*** ' . tohtml($msg) . ' ***' . 
			($noback ? 
			'' : 
			' <input type="button" value="&lt;&lt; Back" onclick="history.back();" />' ) . 
			'</p>';
	else
		return '<p id="hide3" class="msgblue">+++ ' . tohtml($msg) . ' +++</p>';
}

// -------------------------------------------------------------

function errorbutton($msg) {
	if (substr($msg,0,5) == "Error" )
		return '<input type="button" value="&lt;&lt; Back" onclick="history.back();" />';
	else
		return '';
} 

// -------------------------------------------------------------

function runsql($sql, $m) {
	$res = mysql_query($sql);		
	if ($res == FALSE) {
		$message = "Error: " . mysql_error();
	} else {
		$num = mysql_affected_rows();
		$message = (($m == '') ? $num : ($m . ": " . $num));
	}
	return $message;
}

// -------------------------------------------------------------

function optimizedb() {
	adjust_autoincr('archivedtexts','AtID');
	adjust_autoincr('languages','LgID');
	adjust_autoincr('sentences','SeID');
	adjust_autoincr('textitems','TiID');
	adjust_autoincr('texts','TxID');
	adjust_autoincr('words','WoID');
	$dummy = runsql('OPTIMIZE TABLE archivedtexts,languages,sentences,textitems,texts,words,settings', '');
}

// -------------------------------------------------------------

function limitlength($s, $l) {
	if (mb_strlen ($s, 'UTF-8') <= $l) return $s;
	return mb_substr($s, 0, $l, 'UTF-8');
}

// -------------------------------------------------------------

function adjust_autoincr($table,$key) {
	$val = get_first_value('select max(' . $key .')+1 as value from ' . $table);
	if (! isset($val)) $val = 1;
	$sql = 'alter table ' . $table . ' AUTO_INCREMENT = ' . $val;
	$res = mysql_query($sql);		
}

// -------------------------------------------------------------

function prepare_textdata($s) {
	return str_replace("\r\n","\n", stripslashes($s));
}

// -------------------------------------------------------------

function prepare_textdata_js($s) {
	$s = convert_string_to_sqlsyntax($s);
	if($s == "NULL") return "''";
	return str_replace("''", "\\'", $s);
}

// -------------------------------------------------------------

function tohtml($s) {
	if (isset($s)) return htmlspecialchars($s, ENT_COMPAT, "UTF-8");
	else return '';
}
 
// -------------------------------------------------------------

function showRequest() {
	echo "<pre>** DEBUGGING **********************************\n";
if (count($_REQUEST)) { echo '$_REQUEST...'; print_r($_REQUEST); }
	if (count($_COOKIE)) { echo '$_COOKIE...'; print_r($_COOKIE); }
	if (count($_FILES)) { echo '$_FILES...'; print_r($_FILES); }
	if (count($_SESSION)) { echo '$_SESSION...'; print_r($_SESSION); }
	echo 'get_version_number()...'; echo get_version_number() . "\n";
	echo "********************************** DEBUGGING **</pre>";
}

// -------------------------------------------------------------

function convert_string_to_sqlsyntax($data) {
	$result = "NULL";
	$data = trim(prepare_textdata($data));
	if($data != "") $result = "'" . mysql_real_escape_string($data) . "'";
	return $result;
}

// -------------------------------------------------------------

function convert_string_to_sqlsyntax_notrim_nonull($data) {
	return "'" . mysql_real_escape_string(prepare_textdata($data)) . "'";
}

// -------------------------------------------------------------

function remove_spaces($s,$remove) {
	if ($remove) 
		return preg_replace('/\s{1,}/u', '', $s);  // '' enthält &#x200B;
	else
		return $s;
}

// -------------------------------------------------------------

function getreq($s) {
	if ( isset($_REQUEST[$s]) ) {
		return trim($_REQUEST[$s]);
	} else
		return '';
}

// -------------------------------------------------------------

function getsess($s) {
	if ( isset($_SESSION[$s]) ) {
		return trim($_SESSION[$s]);
	} else
		return '';
}

// -------------------------------------------------------------

function getSetting($key) {
	$val = get_first_value('select StValue as value from settings where StKey = ' . convert_string_to_sqlsyntax($key));
	if ( isset($val) ) {
		$val = trim($val);
		if ($key == 'currentlanguage' ) $val = validateLang($val);
		if ($key == 'currenttext' ) $val = validateText($val);
		return $val;
	}
	else return '';
}

// -------------------------------------------------------------

function getSettingWithDefault($key) {
	$dft = get_setting_data();
	$val = get_first_value('select StValue as value from settings where StKey = ' . convert_string_to_sqlsyntax($key));
	if ( isset($val) && $val != '' ) return trim($val);
	else {
		if (array_key_exists($key,$dft)) return $dft[$key]['dft'];
		else return '';
	}
}

// -------------------------------------------------------------

function get_audioplayer_selectoptions($v) {
	if ( ! isset($v) ) $v = "jplayer.blue.monday.modified";
	$r  = "<option value=\"jplayer.blue.monday.modified\"" . get_selected($v,"jplayer.blue.monday.modified");
	$r .= ">Blue Monday Small</option>";
	$r .= "<option value=\"jplayer.blue.monday\"" . get_selected($v,"jplayer.blue.monday");
	$r .= ">Blue Monday</option>";
	$r .= "<option value=\"jplayer-black-and-yellow\"" . get_selected($v,"jplayer-black-and-yellow");
	$r .= ">Black &amp; Yellow</option>";
	return $r;
}

// -------------------------------------------------------------

function get_sentence_count_selectoptions($v) {
	if ( ! isset($v) ) $v = 1;
	$r  = "<option value=\"1\"" . get_selected($v,1);
	$r .= ">Just ONE</option>";
	$r .= "<option value=\"2\"" . get_selected($v,2);
	$r .= ">TWO (+previous)</option>";
	$r .= "<option value=\"3\"" . get_selected($v,3);
	$r .= ">THREE (+previous,+next)</option>";
	return $r;
}

// -------------------------------------------------------------

function saveSetting($k,$v) {
	$dft = get_setting_data();
	if (! isset($v)) $v ='';
	$v = stripslashes($v);
	runsql('delete from settings where StKey = ' . convert_string_to_sqlsyntax($k), '');
	if ($v !== '') {
		if (array_key_exists($k,$dft)) {
			if ($dft[$k]['num']) {
				$v = (int) $v;
				if ( $v < $dft[$k]['min'] ) $v = $dft[$k]['dft'];
				if ( $v > $dft[$k]['max'] ) $v = $dft[$k]['dft'];
			}
		}
		$dum = runsql('insert into settings (StKey, StValue) values(' .
			convert_string_to_sqlsyntax($k) . ', ' . 
			convert_string_to_sqlsyntax($v) . ')', '');
	}
}

// -------------------------------------------------------------

function processSessParam($reqkey,$sesskey,$default,$isnum) {
	$result = '';
	if(isset($_REQUEST[$reqkey])) {
		$reqdata = stripslashes(trim($_REQUEST[$reqkey]));
		$_SESSION[$sesskey] = $reqdata;
		$result = $reqdata;
	}
	elseif(isset($_SESSION[$sesskey])) {
		$result = $_SESSION[$sesskey];
	}
	else {
		$result = $default;
	}
	if($isnum) $result = (int)$result;
	return $result;
}

// -------------------------------------------------------------

function processDBParam($reqkey,$dbkey,$default,$isnum) {
	$result = '';
	$dbdata = getSetting($dbkey);
	if(isset($_REQUEST[$reqkey])) {
		$reqdata = stripslashes(trim($_REQUEST[$reqkey]));
		saveSetting($dbkey,$reqdata);
		$result = $reqdata;
	}
	elseif($dbdata != '') {
		$result = $dbdata;
	}
	else {
		$result = $default;
	}
	if($isnum) $result = (int)$result;
	return $result;
}

// -------------------------------------------------------------

function validateLang($currentlang) {
	if ($currentlang != '') {
		if (
			get_first_value(
				'select count(LgID) as value from languages where LgID=' . 
				((int)$currentlang) 
			) == 0
		)  $currentlang = ''; 
	}
	return $currentlang;
}

// -------------------------------------------------------------

function validateText($currenttext) {
	if ($currenttext != '') {
		if (
			get_first_value(
				'select count(TxID) as value from texts where TxID=' . 
				((int)$currenttext) 
			) == 0
		)  $currenttext = ''; 
	}
	return $currenttext;
}

// -------------------------------------------------------------

function get_first_value($sql) {
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$num = mysql_num_rows($res);
	$d = NULL;
	if ($num != 0 ) {
		$dsatz = mysql_fetch_assoc($res);
		$d = $dsatz["value"];
	}
	mysql_free_result($res);
	return $d;
}

// -------------------------------------------------------------

function get_last_key() {
	return get_first_value('SELECT LAST_INSERT_ID() as value');		
}

// -------------------------------------------------------------

function get_checked($value) {
	if (! isset($value)) return '';
	if ((int)$value != 0) return ' checked="checked" ';
	return '';
}

// -------------------------------------------------------------

function get_selected($value,$selval) {
	if (! isset($value)) return '';
	if ($value == $selval) return ' selected="selected" ';
	return '';
}

// -------------------------------------------------------------

function get_languages_selectoptions($v,$dt) {
	$sql = "select LgID, LgName from languages order by LgName";
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	if ( ! isset($v) || trim($v) == '' ) {
		$r = "<option value=\"\" selected=\"selected\">" . $dt . "</option>";
	} else {
		$r = "<option value=\"\">" . $dt . "</option>";
	}
	$num = mysql_num_rows($res);
	if ($num != 0 ) {
		while ($dsatz = mysql_fetch_assoc($res)) {
			$d = $dsatz["LgName"];
			if ( strlen($d) > 30 ) $d = substr($d,0,30) . "...";
			$r .= "<option value=\"" . $dsatz["LgID"] . "\" " . get_selected($v,$dsatz["LgID"]);
			$r .= ">" . tohtml($d) . "</option>";
		}
	}
	mysql_free_result($res);
	return $r;
}

// -------------------------------------------------------------

function get_languagessize_selectoptions($v) {
	if ( ! isset($v) ) $v = 100;
	$r = "<option value=\"100\"" . get_selected($v,100);
	$r .= ">100 %</option>";
	$r .= "<option value=\"150\"" . get_selected($v,150);
	$r .= ">150 %</option>";
	$r .= "<option value=\"200\"" . get_selected($v,200);
	$r .= ">200 %</option>";
	$r .= "<option value=\"250\"" . get_selected($v,250);
	$r .= ">250 %</option>";
	return $r;
}

// -------------------------------------------------------------

function get_wordstatus_radiooptions($v) {
	if ( ! isset($v) ) $v = 1;
	$r = "";
	$statuses = get_statuses();
	foreach ($statuses as $n => $status) {
		$r .= '<span class="status' . $n . '" title="' . tohtml($status["name"]) . '">';
		$r .= '&nbsp;<input type="radio" name="WoStatus" value="' . $n . '"';
		if ($v == $n) $r .= ' checked="checked"';
		$r .= ' />' . tohtml($status["abbr"]) . "&nbsp;</span> ";
	}
	return $r;
}

// -------------------------------------------------------------

function get_wordstatus_selectoptions($v, $all, $not9899, $off=true) {
	if ( ! isset($v) ) {
		if ( $all ) $v = "";
		else $v = 1;
	}
	$r = "";
	if ($all && $off) {
		$r .= "<option value=\"\"" . get_selected($v,'');
		$r .= ">[Filter off]</option>";
	}
	$statuses = get_statuses();
	foreach ($statuses as $n => $status) {
		if ($not9899 && ($n == 98 || $n == 99)) continue;
		$r .= "<option value =\"" . $n . "\"" . get_selected($v,$n);
		$r .= ">" . tohtml($status['name']) . " [" . 
		tohtml($status['abbr']) . "]</option>";
	}
	if ($all) {
		$status_1_name = tohtml($statuses[1]["name"]);
		$status_1_abbr = tohtml($statuses[1]["abbr"]);
		$r .= "<option value=\"12\"" . get_selected($v,12);
		$r .= ">" . $status_1_name . " [" . $status_1_abbr . ".." . 
		tohtml($statuses[2]["abbr"]) . "]</option>";
		$r .= "<option value=\"13\"" . get_selected($v,13);
		$r .= ">" . $status_1_name . " [" . $status_1_abbr . ".." . 
		tohtml($statuses[3]["abbr"]) . "]</option>";
		$r .= "<option value=\"14\"" . get_selected($v,14);
		$r .= ">" . $status_1_name . " [" . $status_1_abbr . ".." . 
		tohtml($statuses[4]["abbr"]) . "]</option>";
		$r .= "<option value=\"15\"" . get_selected($v,15);
		$r .= ">All [" . $status_1_abbr . ".." . 
		tohtml($statuses[5]["abbr"]) . "]</option>";
		$r .= "<option value=\"599\"" . get_selected($v,599);
		$r .= ">All known [" . tohtml($statuses[5]["abbr"]) . "+" . 
		tohtml($statuses[99]["abbr"]) . "]</option>";
	}
	return $r;
}

// -------------------------------------------------------------

function get_paging_selectoptions($currentpage, $pages) {
	$r = "";
	for ($i=1; $i<=$pages; $i++) {
		$r .= "<option value=\"" . $i . "\"" . get_selected($i, $currentpage);
		$r .= ">$i</option>";
	}
	return $r;
}

// -------------------------------------------------------------

function get_wordssort_selectoptions($v) {
	if ( ! isset($v) ) $v = 1;
	$r  = "<option value=\"1\"" . get_selected($v,1);
	$r .= ">Term A-Z</option>";
	$r .= "<option value=\"2\"" . get_selected($v,2);
	$r .= ">Translation A-Z</option>";
	$r .= "<option value=\"3\"" . get_selected($v,3);
	$r .= ">Newest first</option>";
	$r .= "<option value=\"4\"" . get_selected($v,4);
	$r .= ">Status</option>";
	$r .= "<option value=\"5\"" . get_selected($v,5);
	$r .= ">Score Value (%)</option>";
	return $r;
}

// -------------------------------------------------------------

function get_textssort_selectoptions($v) { 
	if ( ! isset($v) ) $v = 1;
	$r  = "<option value=\"1\"" . get_selected($v,1);
	$r .= ">Title A-Z</option>";
	$r .= "<option value=\"2\"" . get_selected($v,2);
	$r .= ">Newest first</option>"; 
	return $r;
}

// -------------------------------------------------------------

function get_yesno_selectoptions($v) {
	if ( ! isset($v) ) $v = 0;
	$r  = "<option value=\"0\"" . get_selected($v,0);
	$r .= ">No</option>";
	$r .= "<option value=\"1\"" . get_selected($v,1);
	$r .= ">Yes</option>";
	return $r;
}

// -------------------------------------------------------------

function get_set_status_option($n, $suffix = "") {
	return "<option value=\"s" . $n . $suffix . "\">Set Status to " .
		tohtml(get_status_name($n)) . " [" . tohtml(get_status_abbr($n)) .
		"]</option>";
}

// -------------------------------------------------------------

function get_status_name($n) {
	$statuses = get_statuses();
	return $statuses[$n]["name"];
}

// -------------------------------------------------------------

function get_status_abbr($n) {
	$statuses = get_statuses();
	return $statuses[$n]["abbr"];
}

// -------------------------------------------------------------

function get_multiplewordsactions_selectoptions() {
	$r = "<option value=\"\" selected=\"selected\">[Choose...]</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"test\">Test Marked Terms</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"spl1\">Increase Status by 1 [+1]</option>";
	$r .= "<option value=\"smi1\">Reduce Status by 1 [-1]</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= get_set_status_option(1);
	$r .= get_set_status_option(5);
	$r .= get_set_status_option(99);
	$r .= get_set_status_option(98);
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"today\">Set Status Date to Today</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"exp\">Export Marked Terms (Anki)</option>";
	$r .= "<option value=\"exp2\">Export Marked Terms (TSV)</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"del\">Delete Marked Terms</option>";
	return $r;
}

// -------------------------------------------------------------

function get_allwordsactions_selectoptions() {
	$r = "<option value=\"\" selected=\"selected\">[Choose...]</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"testall\">Test ALL Terms</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"spl1all\">Increase Status by 1 [+1]</option>";
	$r .= "<option value=\"smi1all\">Reduce Status by 1 [-1]</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= get_set_status_option(1, "all");
	$r .= get_set_status_option(5, "all");
	$r .= get_set_status_option(99, "all");
	$r .= get_set_status_option(98, "all");
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"todayall\">Set Status Date to Today</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"expall\">Export ALL Terms (Anki)</option>";
	$r .= "<option value=\"expall2\">Export ALL Terms (TSV)</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"delall\">Delete ALL Terms</option>";
	return $r;
}

// -------------------------------------------------------------

function get_multipletextactions_selectoptions() {
	$r = "<option value=\"\" selected=\"selected\">[Choose...]</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"test\">Test Marked Texts</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"del\">Delete Marked Texts</option>";
	$r .= "<option value=\"arch\">Archive Marked Texts</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"rebuild\">Re-Parse Texts</option>";
	$r .= "<option value=\"setsent\">Set Term Sentences</option>";
	return $r;
}

// -------------------------------------------------------------

function get_multiplearchivedtextactions_selectoptions() {
	$r = "<option value=\"\" selected=\"selected\">[Choose...]</option>";
	$r .= "<option disabled=\"disabled\">------------</option>";
	$r .= "<option value=\"del\">Delete Marked Texts</option>";
	$r .= "<option value=\"unarch\">Unarchive Marked Texts</option>";
	return $r;
}

// -------------------------------------------------------------

function get_texts_selectoptions($lang,$v) {
	if ( ! isset($v) ) $v = '';
	if ( ! isset($lang) ) $lang = '';	
	if ( $lang=="" ) 
		$l = "";	
	else 
		$l = "and TxLgID=" . $lang;
	$r = "<option value=\"\"" . get_selected($v,'');
	$r .= ">[Filter off]</option>";
	$sql = "select TxID, TxTitle, LgName from languages, texts where LgID = TxLgID " . $l . " order by LgName, TxTitle";
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$num = mysql_num_rows($res);
	if ($num != 0 ) {
		while ($dsatz = mysql_fetch_assoc($res)) {
			$d = $dsatz["TxTitle"];
			if ( strlen($d) > 30 ) $d = substr($d,0,30) . "...";
			$r .= "<option value=\"" . $dsatz["TxID"] . "\"" . get_selected($v,$dsatz["TxID"]) . ">" . tohtml( ($lang!="" ? "" : ($dsatz["LgName"] . ": ")) . $d) . "</option>";
		}
	}
	mysql_free_result($res);
	return $r;
}

// -------------------------------------------------------------

function makePager ($currentpage, $pages, $script, $formname) {
	if ($currentpage > 1) { 
?>
&nbsp; &nbsp;<a href="<?php echo $script; ?>?page=1"><img src="icn/control-stop-180.png" title="First Page" alt="First Page" /></a>&nbsp;
<a href="<?php echo $script; ?>?page=<?php echo $currentpage-1; ?>"><img  src="icn/control-180.png" title="Previous Page" alt="Previous Page" /></a>&nbsp;
<?php
	} else {
?>
&nbsp; &nbsp;<img src="icn/placeholder.png" alt="-" />&nbsp;
<img src="icn/placeholder.png" alt="-" />&nbsp;
<?php
	} 
?>
Page
<?php
	if ($pages==1) echo '1';
	else {
?>
<select name="page" onchange="{val=document.<?php echo $formname; ?>.page.options[document.<?php echo $formname; ?>.page.selectedIndex].value; location.href='<?php echo $script; ?>?page=' + val;}"><?php echo get_paging_selectoptions($currentpage, $pages); ?></select>
<?php
	}
	echo ' of ' . $pages . '&nbsp; ';
	if ($currentpage < $pages) { 
?>
<a href="<?php echo $script; ?>?page=<?php echo $currentpage+1; ?>"><img src="icn/control.png" title="Next Page" alt="Next Page" /></a>&nbsp;
<a href="<?php echo $script; ?>?page=<?php echo $pages; ?>"><img src="icn/control-stop.png" title="Last Page" alt="Last Page" /></a>&nbsp; &nbsp;
<?php 
	} else {
?>
<img src="icn/placeholder.png" alt="-" />&nbsp;
<img src="icn/placeholder.png" alt="-" />&nbsp; &nbsp; 
<?php
	}
}

// -------------------------------------------------------------

function makeStatusCondition($fieldname, $status) {
	if ($status > 11 && $status <= 15) {
		return '(WoStatus between 1 and ' . ($status % 10) . ')';
	} elseif ($status == 599) {
		return 'WoStatus in (5,99)';
	} else {
		return 'WoStatus = ' . $status;
	}
}

// -------------------------------------------------------------

function createTheDictLink($u,$t) {
	// Case 1: url without any ###: append UTF-8-term
	// Case 2: url with one ###: substitute UTF-8-term
	// Case 3: url with two ###enc###: substitute enc-encoded-term
	// see http://php.net/manual/en/mbstring.supported-encodings.php for supported encodings
	$url = trim($u);
	$trm = trim($t);
	$pos = stripos ($url, '###');
	if ($pos !== false) {  // ### found
		$pos2 = strripos ($url, '###');
		if ( ($pos2-$pos-3) > 1 ) {  // 2 ### found
			$enc = trim(substr($url, $pos+3, $pos2-$pos-3));
			$r = substr($url, 0, $pos);
			$r .= urlencode(mb_convert_encoding($trm, $enc, 'UTF-8'));
			if (($pos2+3) < strlen($url)) $r .= substr($url, $pos2+3);
		} 
		elseif ( $pos == $pos2 ) {  // 1 ### found
			$r = str_replace("###", ($trm == '' ? '+' : urlencode($trm)), $url);
		}
	}
	else  // no ### found
		$r = $url . urlencode($trm);
	return $r;
}

// -------------------------------------------------------------

function createDictLinksInEditWin($lang,$word,$sentctljs,$openfirst) {
	$sql = 'select LgDict1URI, LgDict2URI, LgGoogleTranslateURI from languages where LgID = ' . $lang;
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$dsatz = mysql_fetch_assoc($res);
	$wb1 = isset($dsatz['LgDict1URI']) ? $dsatz['LgDict1URI'] : "";
	$wb2 = isset($dsatz['LgDict2URI']) ? $dsatz['LgDict2URI'] : "";
	$wb3 = isset($dsatz['LgGoogleTranslateURI']) ? $dsatz['LgGoogleTranslateURI'] : "";
	mysql_free_result($res);
	$r ='';
	if ($openfirst) {
		$r .= '<script type="text/javascript">';
		$r .= "\n//<![CDATA[\n";
		$r .= makeOpenDictStrJS(createTheDictLink($wb1,$word));
		$r .= "//]]>\n</script>\n";
	}
	$r .= 'Lookup Term: ';
	$r .= makeOpenDictStr(createTheDictLink($wb1,$word), "Dict1"); 
	if ($wb2 != "") 
		$r .= makeOpenDictStr(createTheDictLink($wb2,$word), "Dict2"); 
	if ($wb3 != "") 
		$r .= makeOpenDictStr(createTheDictLink($wb3,$word), "GTr") . ' | Sent.: ' . makeOpenDictStrDynSent($wb3, $sentctljs, "GTr"); 
	return $r;
}

// -------------------------------------------------------------

function makeOpenDictStr($url, $txt) {
	$r = '';
	if ($url != '' && $txt != '') {
		if(substr($url,0,8) == '*http://') {
			$r = ' <span class="click" onclick="owin(' . prepare_textdata_js(substr($url,1)) . ');">' . tohtml($txt) . '</span> ';
		} 
		elseif (substr($url,0,7) == 'http://') {
			$r = ' <a href="' . $url . '" target="ru">' . tohtml($txt) . '</a> ';
		} 
	}
	return $r;
}

// -------------------------------------------------------------

function makeOpenDictStrJS($url) {
	$r = '';
	if ($url != '') {
		if(substr($url,0,8) == '*http://') {
			$r = "owin(" . prepare_textdata_js(substr($url,1)) . ");\n";
		} 
		elseif (substr($url,0,7) == 'http://') {
			$r = "top.frames['ru'].location.href=" . prepare_textdata_js($url) . ";\n";
		} 
	}
	return $r;
}

// -------------------------------------------------------------

function makeOpenDictStrDynSent($url, $sentctljs, $txt) {
	$r = '';
	if ($url != '') {
		if(substr($url,0,8) == '*http://') {
			$r = '<span class="click" onclick="translateSentence2(' . prepare_textdata_js(substr($url,1)) . ',' . $sentctljs . ');">' . tohtml($txt) . '</span>';
		} 
		elseif (substr($url,0,7) == 'http://') {
			$r = '<span class="click" onclick="translateSentence(' . prepare_textdata_js($url) . ',' . $sentctljs . ');">' . tohtml($txt) . '</span>';
		} 
	}
	return $r;
}

// -------------------------------------------------------------

function createDictLinksInEditWin2($lang,$sentctljs,$wordctljs) {
	$sql = 'select LgDict1URI, LgDict2URI, LgGoogleTranslateURI from languages where LgID = ' . $lang;
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$dsatz = mysql_fetch_assoc($res);
	$wb1 = isset($dsatz['LgDict1URI']) ? $dsatz['LgDict1URI'] : "";
	if(substr($wb1,0,1) == '*') $wb1 = substr($wb1,1);
	$wb2 = isset($dsatz['LgDict2URI']) ? $dsatz['LgDict2URI'] : "";
	if(substr($wb2,0,1) == '*') $wb2 = substr($wb2,1);
	$wb3 = isset($dsatz['LgGoogleTranslateURI']) ? $dsatz['LgGoogleTranslateURI'] : "";
	if(substr($wb3,0,1) == '*') $wb3 = substr($wb3,1);
	mysql_free_result($res);
	$r ='';
	$r .= 'Lookup Term: ';
	$r .= '<span class="click" onclick="translateWord2(' . prepare_textdata_js($wb1) . ',' . $wordctljs . ');">Dict1</span> ';
	if ($wb2 != "") 
		$r .= '<span class="click" onclick="translateWord2(' . prepare_textdata_js($wb2) . ',' . $wordctljs . ');">Dict2</span> ';
	if ($wb3 != "") 
		$r .= '<span class="click" onclick="translateWord2(' . prepare_textdata_js($wb3) . ',' . $wordctljs . ');">GTr</span> | Sent.: <span class="click" onclick="translateSentence2(' . prepare_textdata_js($wb3) . ',' . $sentctljs . ');">GTr</span>'; 
	return $r;
}

// -------------------------------------------------------------

function checkTest($val, $name) {
	if (! isset($_REQUEST[$name])) return ' ';
	if (! is_array($_REQUEST[$name])) return ' ';
	if (in_array($val,$_REQUEST[$name])) return ' checked="checked" ';
	else return ' ';
}

// -------------------------------------------------------------

function strToHex($string)
{
  $hex='';
  for ($i=0; $i < strlen($string); $i++)
  {
  		$h = dechex(ord($string[$i]));
  		if ( strlen($h) == 1 ) 
  			$hex .= "0" . $h;
  		else
  		  $hex .= $h;
  }
  return strtoupper($hex);
}

// -------------------------------------------------------------

function strToClassName($string)
{
	// escapes everything to "¡xx" but not 0-9, a-z, A-Z, and unicode >= "¢" (hex 00A2, dec 162)
	$l = mb_strlen ($string, 'UTF-8');
	$r = '';
  for ($i=0; $i < $l; $i++)
  {
  	$c = mb_substr($string,$i,1, 'UTF-8');
  	$o = ord($c);
  	if (
  		($o < 48) || 
  		($o > 57 && $o < 65) || 
  		($o > 90 && $o < 97) || 
  		($o > 122 && $o < 162)
  		)
  		$r .= '¡' . strToHex($c);
  	else 
  		$r .= $c;
  }
  return $r;
}

// -------------------------------------------------------------

function anki_export($sql) {
	// WoID, LgRegexpWordCharacters, LgName, WoText, WoTranslation, WoRomanization, WoSentence
	$res = mysql_query($sql);
	$x = '';		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$num = mysql_num_rows($res);
	if ($num != 0 ) {
		while ($dsatz = mysql_fetch_assoc($res)) {
			$sent = tohtml(repl_tab_nl($dsatz["WoSentence"]));
			$sent1 = str_replace("{", '<span style="font-weight:600; color:#0000ff;">[', str_replace("}", ']</span>', 
				mask_term_in_sentence($sent,$dsatz["LgRegexpWordCharacters"])
			));
			$sent2 = str_replace("{", '<span style="font-weight:600; color:#0000ff;">[', str_replace("}", ']</span>', $sent));
			$x .= tohtml(repl_tab_nl($dsatz["WoText"])) . "\t" . 
			tohtml(repl_tab_nl($dsatz["WoTranslation"])) . "\t" . 
			tohtml(repl_tab_nl($dsatz["WoRomanization"])) . "\t" . 
			$sent1 . "\t" . 
			$sent2 . "\t" . 
			tohtml(repl_tab_nl($dsatz["LgName"])) . "\t" . 
			tohtml($dsatz["WoID"]) .  
			"\r\n";
		}
	}
	mysql_free_result($res);
	header('Content-type: text/plain; charset=utf-8');
	header("Content-disposition: attachment; filename=lwt_anki_export.txt");
	echo $x;
	exit();
}

// -------------------------------------------------------------

function tsv_export($sql) {
	// WoID, LgName, WoText, WoTranslation, WoRomanization, WoSentence, WoStatus
	$res = mysql_query($sql);
	$x = '';		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$num = mysql_num_rows($res);
	if ($num != 0 ) {
		while ($dsatz = mysql_fetch_assoc($res)) {
			$x .= repl_tab_nl($dsatz["WoText"]) . "\t" . 
			repl_tab_nl($dsatz["WoTranslation"]) . "\t" . 
			repl_tab_nl($dsatz["WoSentence"]) . "\t" . 
			repl_tab_nl($dsatz["WoRomanization"]) . "\t" . 
			$dsatz["WoStatus"] . "\t" . 
			repl_tab_nl($dsatz["LgName"]) . "\t" . 
			$dsatz["WoID"] . "\r\n";
		}
	}
	mysql_free_result($res);
	header('Content-type: text/plain; charset=utf-8');
	header("Content-disposition: attachment; filename=lwt_tsv_export.txt");
	echo $x;
	exit();
}

// -------------------------------------------------------------

function repl_tab_nl($s) {
	return str_replace("\r",' ',
		str_replace("\n",' ',
		str_replace("\r\n",' ',
		str_replace("\t",' ',$s)
		)));
}

// -------------------------------------------------------------

function mask_term_in_sentence($s,$regexword) {
	$l = mb_strlen($s,'utf-8');
	$r = '';
	$on = 0;
	for ($i=0; $i < $l; $i++) {
		$c = mb_substr($s, $i, 1, 'UTF-8');
		if ($c == '}') $on = 0;
		if ($on) {
			if (preg_match('/[' . $regexword . ']/u', $c)) {
   			$r .= '•';
			} else {
   			$r .= $c;
			}	
		}
		else {
			$r .= $c;
		}
		if ($c == '{') $on = 1;
	}
	return $r;
}

// -------------------------------------------------------------

function textwordcount($text) {
	return get_first_value('select count(distinct TiTextLC) as value from textitems where TiIsNotWord = 0 and TiWordCount = 1 and TiTxID = ' . $text);
}

// -------------------------------------------------------------

function textexprcount($text) {
	return get_first_value('select count(distinct TiTextLC) as value from textitems left join words on TiTextLC = WoTextLC where TiWordCount > 1 and TiIsNotWord = 0 and TiTxID = ' . $text . ' and WoID is not null and TiLgID = WoLgID');
}

// -------------------------------------------------------------

function textworkcount($text) {
	return get_first_value('select count(distinct TiTextLC) as value from textitems left join words on TiTextLC = WoTextLC where TiWordCount = 1 and TiIsNotWord = 0 and TiTxID = ' . $text . ' and WoID is not null and TiLgID = WoLgID');
}

// -------------------------------------------------------------

function texttodocount($text) {
	return '<span title="To Do" class="status0">&nbsp;' . 
	(textwordcount($text) - textworkcount($text)) . '&nbsp;</span>';
}

// -------------------------------------------------------------

function texttodocount2($text) {
	$c = textwordcount($text) - textworkcount($text);
	if ($c > 0 ) 
		return '<span title="To Do" class="status0">&nbsp;' . $c . '&nbsp;</span>&nbsp;&nbsp;&nbsp;<input type="button" onclick="iknowall(' . $text . ');" value=" I KNOW ALL " />';
	else
		return '<span title="To Do" class="status0">&nbsp;' . $c . '&nbsp;</span>';
}

// -------------------------------------------------------------

function getSentence($seid, $wordlc,$mode) {
	$txtid = get_first_value('select SeTxID as value from sentences where SeID = ' . $seid);
	$seidlist = $seid;
	if ($mode > 1) {
		$prevseid = get_first_value('select SeID as value from sentences where SeID < ' . $seid . ' and SeTxID = ' . $txtid . " and trim(SeText) not in ('¶','') order by SeID desc");
		if (isset($prevseid)) $seidlist .= ',' . $prevseid;
		if ($mode > 2) {
			$nextseid = get_first_value('select SeID as value from sentences where SeID > ' . $seid . ' and SeTxID = ' . $txtid . " and trim(SeText) not in ('¶','') order by SeID asc");
			if (isset($nextseid)) $seidlist .= ',' . $nextseid;
		}
	}
	$sql2 = 'SELECT TiText, TiTextLC, TiWordCount, TiIsNotWord FROM textitems WHERE TiSeID in (' . $seidlist . ') and TiTxID=' . $txtid . ' order by TiOrder asc, TiWordCount desc';
	$res2 = mysql_query($sql2);		
	if ($res2 == FALSE) die("<p>Invalid query: $sql2</p>");
	$sejs=''; 
	$se='';
	$notfound = 1;
	$jump=0;
	while ($dsatz2 = mysql_fetch_assoc($res2)) {
		if ($dsatz2['TiIsNotWord'] == 1) {
			$jump--;
			if ($jump < 0) {
				$sejs .= $dsatz2['TiText']; 
				$se .= tohtml($dsatz2['TiText']);
			} 
		}	else {
			if (($jump-1) < 0) {
				if ($notfound) {
					if ($dsatz2['TiTextLC'] == $wordlc) { 
						$sejs.='{'; 
						$se.='<b>'; 
						$sejs .= $dsatz2['TiText']; 
						$se .= tohtml($dsatz2['TiText']); 
						$sejs.='}'; 
						$se.='</b>';
						$notfound = 0;
						$jump=($dsatz2['TiWordCount']-1)*2; 
					}
				}
				if ($dsatz2['TiWordCount'] == 1) {
					if ($notfound) {
						$sejs .= $dsatz2['TiText']; 
						$se .= tohtml($dsatz2['TiText']);
						$jump=0;  
					}	else {
						$notfound = 1;
					}
				}
			} else {
				if ($dsatz2['TiWordCount'] == 1) $jump--; 
			}
		}
	}
	mysql_free_result($res2);
	return array($se,$sejs); // [0]=html, word in bold
	                         // [1]=text, word in {} 
}

// -------------------------------------------------------------

function get20Sentences($lang, $wordlc, $jsctlname, $mode) {
	$r = '<p class="bigger"><b>Sentences with "' . tohtml($wordlc) . '"</b></p><p>(Click on <img src="icn/tick-button.png" title="Choose" alt="Choose" /> to copy sentence into above term)</p>';
	$sql = 'SELECT DISTINCT SeID, SeText FROM sentences, textitems WHERE TiTextLC = ' . convert_string_to_sqlsyntax($wordlc) . ' AND SeID = TiSeID AND SeLgID = ' . $lang . ' order by CHAR_LENGTH(SeText), SeText limit 0,20';
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$r .= '<p>';
	$last = '';
	while ($dsatz = mysql_fetch_assoc($res)) {
		if ($last != $dsatz['SeText']) {
			$sent = getSentence($dsatz['SeID'], $wordlc,$mode);
			$r .= '<span class="click" onclick="' . $jsctlname . '.value=' . prepare_textdata_js($sent[1]) . ';"><img src="icn/tick-button.png" title="Choose" alt="Choose" /></span> &nbsp;' . $sent[0] . '<br />';
		}
		$last = $dsatz['SeText'];
	}
	mysql_free_result($res);
	$r .= '</p>';
	return $r;
}

// -------------------------------------------------------------

function getsqlscoreformula ($method) {
	// $method = 1 (int 0..100)
	// $method = 2 (float unlimited)
	// Formula: {{{2.4^{Status}+Status-Days-1} over Status -2.4} over 0.14325248}
		
	$baseformula = '(((POWER(2.4,WoStatus) + WoStatus - DATEDIFF(NOW(),WoStatusChanged) - 1) / WoStatus - 2.4) / 0.14325248)';
	
	if ($method == 2) return 'CASE WHEN WoStatus > 5 THEN 100 ELSE ' . $baseformula . ' END';
	
	else return 'CASE WHEN WoStatus > 5 THEN 100 ELSE greatest(0,round(' . $baseformula . ',0)) END';
}

// -------------------------------------------------------------

function AreUnknownWordsInSentence ($sentno) {
	$x = get_first_value("SELECT distinct ifnull(WoTextLC,'') as value FROM (textitems left join words on (TiTextLC = WoTextLC) and (TiLgID = WoLgID)) where TiSeID = " . $sentno . " AND TiWordCount = 1 AND TiIsNotWord = 0 order by WoTextLC asc limit 1");
	// echo $sentno . '/' . isset($x) . '/' . $x . '/';
	if ( isset($x) ) {
		if ( $x == '' ) return true;
	}
	return false;
}

// -------------------------------------------------------------

function get_statuses() {
	static $statuses;
	if (!$statuses) {
		$statuses = array(
				 1 => array("abbr" =>   "1", "name" => "Learning"),
				 2 => array("abbr" =>   "2", "name" => "Learning"),
				 3 => array("abbr" =>   "3", "name" => "Learning"),
				 4 => array("abbr" =>   "4", "name" => "Learning"),
				 5 => array("abbr" =>   "5", "name" => "Learned"),
				99 => array("abbr" => "WKn", "name" => "Well Known"),
				98 => array("abbr" => "Ign", "name" => "Ignored"),
		);
	}
	return $statuses;
}

// -------------------------------------------------------------

function get_setting_data() {
	static $setting_data;
	if (! $setting_data) {
		$setting_data = array(
		'set-text-h-frameheight-no-audio' => 
		array("dft" => '140', "num" => 1, "min" => 10, "max" => 999),
		'set-text-h-frameheight-with-audio' => 
		array("dft" => '200', "num" => 1, "min" => 10, "max" => 999),
		'set-text-l-framewidth-percent' => 
		array("dft" => '50', "num" => 1, "min" => 5, "max" => 95),
		'set-text-r-frameheight-percent' => 
		array("dft" => '50', "num" => 1, "min" => 5, "max" => 95),
		'set-test-h-frameheight' => 
		array("dft" => '140', "num" => 1, "min" => 10, "max" => 999),
		'set-test-l-framewidth-percent' => 
		array("dft" => '50', "num" => 1, "min" => 5, "max" => 95),
		'set-test-r-frameheight-percent' => 
		array("dft" => '50', "num" => 1, "min" => 5, "max" => 95),
		'set-player-skin-name' => 
		array("dft" => 'jplayer.blue.monday.modified', "num" => 0),
		'set-test-main-frame-waiting-time' => 
		array("dft" => '200', "num" => 1, "min" => 0, "max" => 9999),
		'set-test-edit-frame-waiting-time' => 
		array("dft" => '2000', "num" => 1, "min" => 0, "max" => 99999999),
		'set-test-sentence-count' => 
		array("dft" => '1', "num" => 0),
		'set-term-sentence-count' => 
		array("dft" => '1', "num" => 0),
		'set-archivedtexts-per-page' => 
		array("dft" => '100', "num" => 1, "min" => 1, "max" => 9999),
		'set-texts-per-page' => 
		array("dft" => '10', "num" => 1, "min" => 1, "max" => 9999),
		'set-terms-per-page' => 
		array("dft" => '100', "num" => 1, "min" => 1, "max" => 9999),
		'set-show-text-word-counts' => 
		array("dft" => '1', "num" => 0)
		);
	}
	return $setting_data;
}

// -------------------------------------------------------------

function getLanguage($lid) {
	if ( ! isset($lid) ) return '';
	if ( trim($lid) == '' ) return '';
	if ( ! is_numeric($lid) ) return '';
	$r = get_first_value("select LgName as value from languages where LgID='" . $lid . "'");
	if ( isset($r) ) return $r;
	return '';
}

// -------------------------------------------------------------

function echodebug($var,$text) {
	global $debug;
	if (! $debug ) return;
	echo "<pre> **DEBUGGING** " . tohtml($text) . ' = [[[';
	print_r($var);
	echo "]]]\n--------------</pre>";
}

// -------------------------------------------------------------

function checkText($text, $lid) {
	
	$r = '';

	$sql = "select * from languages where LgID=" . $lid;
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$num = mysql_num_rows($res);
	if ($num == 0 ) die("<p>No results: $sql</p>");
	$dsatz = mysql_fetch_assoc($res);
	$removeSpaces = $dsatz['LgRemoveSpaces'];
	echodebug($removeSpaces,'$removeSpaces');
	$splitEachChar = $dsatz['LgSplitEachChar'];
	echodebug($splitEachChar,'$splitEachChar');
	$satzende = $dsatz['LgRegexpSplitSentences'];
	echodebug($satzende,'$satzende');
	$keinsatzende = $dsatz['LgExceptionsSplitSentences'];
	echodebug($keinsatzende,'$keinsatzende');
	$wortzeichen = $dsatz['LgRegexpWordCharacters'];
	echodebug($wortzeichen,'$wortzeichen');
	$replace = explode("|",$dsatz['LgCharacterSubstitutions']);
	echodebug($replace,'$replace');
	mysql_free_result($res);
	echodebug($text,'$text');
	$s = prepare_textdata($text);
	echodebug($s,'$s/1');
	$s = str_replace("\n", " ¶ ", $s);
	echodebug($s,'$s/2');
	$s = str_replace("\t", " ", $s);
	$s = trim($s);
	echodebug($s,'$s/3');
	if ($splitEachChar) {
		$s = preg_replace('/([^\s])/u', "$1 ", $s);
	}
	echodebug($s,'$s/4');
	$s = preg_replace('/\s{2,}/u', ' ', $s);
	echodebug($s,'$s/5');
	$r .= "<h4>Text</h4><p>" . str_replace("¶", "<br /><br />", tohtml($s)). "</p>";

	$s = str_replace('{', '[', $s);	// because of sent. spc. char
	echodebug($s,'$s/6');
	$s = str_replace('}', ']', $s);	
	echodebug($s,'$s/7');
	foreach ($replace as $value) {
		$fromto = explode("=",trim($value));
		if(count($fromto) >= 2) {
  		$s = str_replace(trim($fromto[0]), trim($fromto[1]), $s);
		}
	}
	$s = trim($s);
	echodebug($s,'$s/8');
	
	if ($keinsatzende != '') $s = preg_replace('/(' . $keinsatzende . ')\s/u', '$1‧', $s);
	echodebug($s,'$s/9');
	$s = preg_replace('/([' . $satzende . '¶])\s/u', "$1\n", $s);
	echodebug($s,'$s/10');
	$s = str_replace(" ¶\n", "\n¶\n", $s);
	echodebug($s,'$s/11');
	$s = str_replace('‧', ' ', $s);
	echodebug($s,'$s/12');
	
	if ($s=='') {
		$zeilen = array($s);
	} else {
		$s = explode("\n",$s);
		$l = count($s);
		for ($i=0; $i<$l; $i++) {
  		$s[$i] = trim($s[$i]);
  		if ($s[$i] != '') {
	  		$pos = strpos($satzende, $s[$i]);
	  		while ($pos !== false && $i > 0) {
	  			$s[$i-1] .= " " . $s[$i];
	  			for ($j=$i+1; $j<$l; $j++) $s[$j-1] = $s[$j];
	  			array_pop($s);
	  			$l = count($s);
	  			$pos = strpos($satzende, $s[$i]);
	  		}
  		}
		}
		$l = count($s);
		$zeilen = array();
		for ($i=0; $i<$l; $i++) {
			$zz = trim($s[$i]);
			if ($zz != '' ) $zeilen[] = $zz;
		}
	}
	echodebug($zeilen,'$zeilen');
	
	$zeilenworte = array();
	$wortliste = array();
	$wortindex = array();
	$worttrenn = array();

	$r .= "<h4>Sentences</h4><ol>";
	$satznr = 0;
	foreach ($zeilen as $value) { 
		$r .= "<li>" . tohtml(remove_spaces($value, $removeSpaces)) . "</li>";
		$zeilenworte[$satznr] = preg_split('/([^' . $wortzeichen . ']{1,})/u', $value, -1, PREG_SPLIT_DELIM_CAPTURE );
		$l = count($zeilenworte[$satznr]);
		for ($i=0; $i<$l; $i++) {
			$wort = mb_strtolower($zeilenworte[$satznr][$i], 'UTF-8');
			if ($wort != '') {
				if ($i % 2 == 0) {
					if(array_key_exists($wort,$wortliste)) {
						$wortliste[$wort][0]++;
						$wortliste[$wort][1][] = $satznr;
					}
					else {
						$wortliste[$wort] = array(1, array($satznr));
						$wortindex[] = $wort;
					}
				} else {
					$ww = remove_spaces($wort, $removeSpaces);
					if(array_key_exists($ww,$worttrenn))
						$worttrenn[$ww]++;
					else	
						$worttrenn[$ww]=1;
				}
			}
		}
		$satznr += 1;
	} 
	$r .= "</ol><h4>Word List <span class=\"red2\">(red = already saved)</span></h4><ul>";
	ksort($wortliste); 
	$anz = 0;
	foreach ($wortliste as $key => $value) {
		$trans = get_first_value("select WoTranslation as value from words where WoLgID = " . $lid . " and WoTextLC = " . convert_string_to_sqlsyntax($key));
		if (! isset($trans)) $trans="";
		if ($trans == "*") $trans="";
		if ($trans != "") 
			$r .= "<li><span class=\"red2\">[" . tohtml($key) . "] — " . $value[0] . " - " . tohtml(repl_tab_nl($trans)) . "</span></li>";
		else
			$r .= "<li>[" . tohtml($key) . "] — " . $value[0] . "</li>";	
		$anz++;
	} 
	$r .= "</ul><p>TOTAL: " . $anz . "</p><h4>Non-Word List</h4><ul>";
	if(array_key_exists('',$worttrenn)) unset($worttrenn['']);
	ksort($worttrenn); 
	$anz = 0;
	foreach ($worttrenn as $key => $value) { 
		$r .= "<li>[" . str_replace(" ", "<span class=\"backgray\">&nbsp;</span>", tohtml($key)) . "] — " . $value . "</li>";
		$anz++;
	} 
	$r .=  "</ul><p>TOTAL: " . $anz . "</p>"; 

	return $r;
}

// -------------------------------------------------------------

function splitText($text, $lid, $id) {

	$sql = "select * from languages where LgID=" . $lid;
	$res = mysql_query($sql);		
	if ($res == FALSE) die("<p>Invalid query: $sql</p>");
	$num = mysql_num_rows($res);
	if ($num == 0 ) die("<p>No results: $sql</p>");
	$dsatz = mysql_fetch_assoc($res);
	$removeSpaces = $dsatz['LgRemoveSpaces'];
	$splitEachChar = $dsatz['LgSplitEachChar'];
	$satzende = $dsatz['LgRegexpSplitSentences'];
	$keinsatzende = $dsatz['LgExceptionsSplitSentences'];
	$wortzeichen = $dsatz['LgRegexpWordCharacters'];
	$replace = explode("|",$dsatz['LgCharacterSubstitutions']);
	mysql_free_result($res);
	$s = str_replace("\r\n", "\n", $text);
	$s = str_replace("\n", " ¶ ", $s);
	$s = str_replace("\t", " ", $s);
	$s = trim($s);
	if ($splitEachChar) {
		$s = preg_replace('/([^\s])/u', "$1 ", $s);
	}
	$s = preg_replace('/\s{2,}/u', ' ', $s);
	
	$s = str_replace('{', '[', $s);	// because of sent. spc. char
	$s = str_replace('}', ']', $s);	
	foreach ($replace as $value) {
		$fromto = explode("=",trim($value));
		if(count($fromto) >= 2) {
  		$s = str_replace(trim($fromto[0]), trim($fromto[1]), $s);
		}
	}
	$s = trim($s);
	
	if ($keinsatzende != '') $s = preg_replace('/(' . $keinsatzende . ')\s/u', '$1‧', $s);
	$s = preg_replace('/([' . $satzende . '¶])\s/u', "$1\n", $s);
	$s = str_replace(" ¶\n", "\n¶\n", $s);
	$s = str_replace('‧', ' ', $s);
	
	if ($s=='') {
		$zeilen = array($s);
	} else {
		$s = explode("\n",$s);
		$l = count($s);
		for ($i=0; $i<$l; $i++) {
  		$s[$i] = trim($s[$i]);
  		if ($s[$i] != '') {
	  		$pos = strpos($satzende, $s[$i]);
	  		while ($pos !== false && $i > 0) {
	  			$s[$i-1] .= " " . $s[$i];
	  			for ($j=$i+1; $j<$l; $j++) $s[$j-1] = $s[$j];
	  			array_pop($s);
	  			$l = count($s);
	  			$pos = strpos($satzende, $s[$i]);
	  		}
  		}
		}
		$l = count($s);
		$zeilen = array();
		for ($i=0; $i<$l; $i++) {
			$zz = trim($s[$i]);
			if ($zz != '' ) $zeilen[] = $zz;
		}
	}
	
	$zeilenworte = array();
	$wortliste = array();
	$wortindex = array();
	$worttrenn = array();
	$satznr = 0;
	$lfdnr =0;

	foreach ($zeilen as $value) { 
		
		$dummy = runsql('INSERT INTO sentences (SeLgID, SeTxID, SeOrder, SeText) VALUES (' . $lid . ',' .  $id . ',' .  ($satznr+1) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($value . ' ', $removeSpaces)) . ')', ' ');
		$sentid = get_last_key();
		/**** Speichern Sätze Ende ***/
		$zeilenworte[$satznr] = preg_split('/([^' . $wortzeichen . ']+)/u', $value . ' ', null, PREG_SPLIT_DELIM_CAPTURE );
		$l = count($zeilenworte[$satznr]);
		$sqltext = 'INSERT INTO textitems (TiLgID, TiTxID, TiSeID, TiOrder, TiWordCount, TiText, TiTextLC, TiIsNotWord) VALUES ';
		$lfdnr1=0;
		for ($i=0; $i<$l; $i++) {
			$wort = mb_strtolower($zeilenworte[$satznr][$i], 'UTF-8');
			$rest2 = '';
			$rest3 = '';
			$rest4 = '';
			$rest5 = '';
			$rest6 = '';
			$rest7 = '';
			$rest8 = '';
			$rest9 = '';
			$restlc2 = '';
			$restlc3 = '';
			$restlc4 = '';
			$restlc5 = '';
			$restlc6 = '';
			$restlc7 = '';
			$restlc8 = '';
			$restlc9 = '';
			if ($wort != '') {
				if ($i % 2 == 0) {
					$isnotwort=0;
					$rest = $zeilenworte[$satznr][$i];
					$cnt = 0;
					for ($j=$i+1; $j<$l; $j++) {
						if ($zeilenworte[$satznr][$j] != '') {
							$rest .= $zeilenworte[$satznr][$j]; $cnt++;
							if($cnt == 2) { $rest2 = $rest; $restlc2 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 4) { $rest3 = $rest; $restlc3 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 6) { $rest4 = $rest; $restlc4 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 8) { $rest5 = $rest; $restlc5 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 10) { $rest6 = $rest; $restlc6 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 12) { $rest7 = $rest; $restlc7 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 14) { $rest8 = $rest; $restlc8 = mb_strtolower($rest, 'UTF-8'); }
							if($cnt == 16) { $rest9 = $rest; $restlc9 = mb_strtolower($rest, 'UTF-8'); break; }
						}
					}
				} else {
					$isnotwort=1;
				}
				
				$lfdnr++;
				$lfdnr1++;
				if ($lfdnr1 > 1) $sqltext .= ',';
				$sqltext .= '(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 1, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($zeilenworte[$satznr][$i], $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($wort, $removeSpaces)) . ',' . $isnotwort . ')';
				if ($isnotwort==0) {
					if ($rest2 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 2, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest2, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc2, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest3 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 3, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest3, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc3, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest4 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 4, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest4, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc4, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest5 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 5, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest5, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc5, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest6 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 6, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest6, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc6, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest7 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 7, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest7, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc7, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest8 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 8, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest8, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc8, $removeSpaces)) . ',' . $isnotwort . ')';
					if ($rest9 != '') $sqltext .= ',(' . $lid . ',' .  $id . ',' .  $sentid . ',' . $lfdnr . ', 9, ' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($rest9, $removeSpaces)) . ',' . convert_string_to_sqlsyntax_notrim_nonull(remove_spaces($restlc9, $removeSpaces)) . ',' . $isnotwort . ')';
				}
			}
		}
		if ($lfdnr > 0) $dummy = runsql($sqltext,'');
		$satznr += 1;
	} 

}

// -------------------------------------------------------------

function refreshText($word,$tid) {
	// $word : only sentences with $word
	// $tid : textid
	// only to be used when $showAll = 0 !
	$out = '';
	$wordlc = trim(mb_strtolower($word, 'UTF-8'));
	if ( $wordlc == '') return '';
	$sql = 'SELECT distinct TiSeID FROM textitems WHERE TiIsNotWord = 0 and TiTextLC = ' . convert_string_to_sqlsyntax($wordlc) . ' and TiTxID = ' . $tid . ' order by TiSeID';
	$res = mysql_query($sql);		
	if ($res == FALSE) return '';
	$inlist = '(';
	while ($dsatz = mysql_fetch_assoc($res)) { 
		if ($inlist == '(') 
			$inlist .= $dsatz['TiSeID'];
		else
			$inlist .= ',' . $dsatz['TiSeID'];
	}
	mysql_free_result($res);
	if ($inlist == '(') 
		return '';
	else
		$inlist =  ' where TiSeID in ' . $inlist . ') ';
	$sql = 'select TiWordCount as Code, TiOrder, TiIsNotWord, WoID from (textitems left join words on (TiTextLC = WoTextLC) and (TiLgID = WoLgID)) ' . $inlist . ' order by TiOrder asc, TiWordCount desc';

	$res = mysql_query($sql);		
	if ($res == FALSE) return '';

	$hideuntil = -1;
	$hidetag = "removeClass('hide');";

	while ($dsatz = mysql_fetch_assoc($res)) {  // MAIN LOOP
		$actcode = $dsatz['Code'] + 0;
		$order = $dsatz['TiOrder'] + 0;
		$notword = $dsatz['TiIsNotWord'] + 0;
		$termex = isset($dsatz['WoID']);
		$spanid = 'ID-' . $order . '-' . $actcode;

		if ( $hideuntil > 0 ) {
			if ( $order <= $hideuntil )
				$hidetag = "addClass('hide');";
			else {
				$hideuntil = -1;
				$hidetag = "removeClass('hide');";
			}
		}

		if ($notword != 0) {  // NOT A TERM
			$out .= "$('#" . $spanid . "',context)." . $hidetag . "\n";
		}  

		else {   // A TERM
			if ($actcode > 1) {   // A MULTIWORD FOUND
				if ($termex) {  // MULTIWORD FOUND - DISPLAY 
					if ($hideuntil == -1) $hideuntil = $order + ($actcode - 1) * 2;
					$out .= "$('#" . $spanid . "',context)." . $hidetag . "\n";
				}
				else {  // MULTIWORD PLACEHOLDER - NO DISPLAY 
					$out .= "$('#" . $spanid . "',context).addClass('hide');\n";
				}  
			} // ($actcode > 1) -- A MULTIWORD FOUND

			else {  // ($actcode == 1)  -- A WORD FOUND
				$out .= "$('#" . $spanid . "',context)." . $hidetag . "\n";
			}  
		}
	} //  MAIN LOOP
	mysql_free_result($res);
	return $out;
}

// -------------------------------------------------------------

function check_update_db() {
	$tables = array();
	
	$res = mysql_query("SHOW TABLES");
	if ($res == FALSE) die("SHOW TABLES error");
  while ($row = mysql_fetch_row($res)) 
  	$tables[] = $row[0];
	mysql_free_result($res);
	
	$count = 0;  // counter for cache rebuild
	
	// Rebuild Tables if missing
	
	if (in_array('archivedtexts', $tables) == FALSE) {
		runsql("CREATE TABLE IF NOT EXISTS archivedtexts ( AtID int(11) unsigned NOT NULL AUTO_INCREMENT, AtLgID int(11) unsigned NOT NULL, AtTitle varchar(200) NOT NULL, AtText text NOT NULL, AtAudioURI varchar(200) DEFAULT NULL, PRIMARY KEY (AtID), KEY AtLgID (AtLgID) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8",'');
	}
	
	if (in_array('languages', $tables) == FALSE) {
		runsql("CREATE TABLE IF NOT EXISTS languages ( LgID int(11) unsigned NOT NULL AUTO_INCREMENT, LgName varchar(40) NOT NULL, LgDict1URI varchar(200) NOT NULL, LgDict2URI varchar(200) DEFAULT NULL, LgGoogleTranslateURI varchar(200) DEFAULT NULL, LgGoogleTTSURI varchar(200) DEFAULT NULL, LgTextSize int(5) unsigned NOT NULL DEFAULT '100', LgCharacterSubstitutions varchar(500) NOT NULL, LgRegexpSplitSentences varchar(500) NOT NULL, LgExceptionsSplitSentences varchar(500) NOT NULL, LgRegexpWordCharacters varchar(500) NOT NULL, LgRemoveSpaces int(1) unsigned NOT NULL DEFAULT '0', LgSplitEachChar int(1) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (LgID), UNIQUE KEY LgName (LgName) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8",'');
	}
	
	if (in_array('sentences', $tables) == FALSE) {
	 runsql("CREATE TABLE IF NOT EXISTS sentences ( SeID int(11) unsigned NOT NULL AUTO_INCREMENT, SeLgID int(11) unsigned NOT NULL, SeTxID int(11) unsigned NOT NULL, SeOrder int(11) unsigned NOT NULL, SeText text, PRIMARY KEY (SeID), KEY SeLgID (SeLgID), KEY SeTxID (SeTxID), KEY SeOrder (SeOrder) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8",'');
		$count++;
	}
	
	if (in_array('settings', $tables) == FALSE) {
		runsql("CREATE TABLE IF NOT EXISTS settings ( StKey varchar(40) NOT NULL, StValue varchar(40) DEFAULT NULL, PRIMARY KEY (StKey) ) ENGINE=MyISAM DEFAULT CHARSET=utf8",'');
	}
	
	if (in_array('textitems', $tables) == FALSE) {
		runsql("CREATE TABLE IF NOT EXISTS textitems ( TiID int(11) unsigned NOT NULL AUTO_INCREMENT, TiLgID int(11) unsigned NOT NULL, TiTxID int(11) unsigned NOT NULL, TiSeID int(11) unsigned NOT NULL, TiOrder int(11) unsigned NOT NULL, TiWordCount int(1) unsigned NOT NULL, TiText varchar(250) NOT NULL, TiTextLC varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, TiIsNotWord tinyint(1) NOT NULL, PRIMARY KEY (TiID), KEY TiLgID (TiLgID), KEY TiTxID (TiTxID), KEY TiSeID (TiSeID), KEY TiOrder (TiOrder), KEY TiTextLC (TiTextLC), KEY TiIsNotWord (TiIsNotWord) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8",'');
		$count++;
	}
	
	if (in_array('texts', $tables) == FALSE) {
		runsql("CREATE TABLE IF NOT EXISTS texts ( TxID int(11) unsigned NOT NULL AUTO_INCREMENT, TxLgID int(11) unsigned NOT NULL, TxTitle varchar(200) NOT NULL, TxText text NOT NULL, TxAudioURI varchar(200) DEFAULT NULL, PRIMARY KEY (TxID), KEY TxLgID (TxLgID) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8",'');
	}
	
	if (in_array('words', $tables) == FALSE) {
		runsql("CREATE TABLE IF NOT EXISTS words ( WoID int(11) unsigned NOT NULL AUTO_INCREMENT, WoLgID int(11) unsigned NOT NULL, WoText varchar(250) NOT NULL, WoTextLC varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, WoStatus tinyint(4) NOT NULL, WoTranslation varchar(500) NOT NULL DEFAULT '*', WoRomanization varchar(100) DEFAULT NULL, WoSentence varchar(1000) DEFAULT NULL, WoCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, WoStatusChanged timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (WoID), UNIQUE KEY WoLgIDTextLC (WoLgID,WoTextLC), KEY WoLgID (WoLgID), KEY WoStatus (WoStatus), KEY WoTextLC (WoTextLC), KEY WoTranslation (WoTranslation(333)), KEY WoCreated (WoCreated), KEY WoStatusChanged (WoStatusChanged) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8",'');
	}
	
	if ($count > 0) {		
		// Rebuild Text Cache if cache tables new
		$sql = "select TxID, TxLgID from texts";
		$res = mysql_query($sql);		
		if ($res == FALSE) die("<p>Invalid query: $sql</p>");
		$num = mysql_num_rows($res);
		if ($num != 0 ) {
			while ($dsatz = mysql_fetch_assoc($res)) {
				$id = $dsatz['TxID'];
				runsql('delete from sentences where SeTxID = ' . $id, "");
				runsql('delete from textitems where TiTxID = ' . $id, "");
				adjust_autoincr('sentences','SeID');
				adjust_autoincr('textitems','TiID');
				splitText(
					get_first_value('select TxText as value from texts where TxID = ' . $id), $dsatz['TxLgID'], $id );
			}
		}
		mysql_free_result($res);
	}
	
	// Version
	
	$res = mysql_query("select StValue as value from settings where StKey = 'dbversion'");
	if (mysql_errno() != 0) die('There is something wrong with your database ' . $dbname . '. Please reinstall.');
	$num = mysql_num_rows($res);
	$dbversion = '';
	if ($num != 0 ) {
		$dsatz = mysql_fetch_assoc($res);
		$dbversion = $dsatz["value"];
	}
	mysql_free_result($res);
	
	if ($dbversion == '') {
		$dbversion = 'v001000000';
		saveSetting('dbversion',$dbversion);
	}
	
	$currversion = get_version_number();
	if ( $currversion > $dbversion ) {
		if ($currversion > 'v001000000') {  
			// no db updates in 1.0.1 etc.
		}
		// set to current.
		saveSetting('dbversion',$currversion);
	}
}

// -------------------------------------------------------------

//////////////////  S T A R T  /////////////////////////////////

// Connection, @ suppresses messages from function

$err = @mysql_connect($server,$userid,$passwd); 
if ($err == FALSE) die('DB connect error (MySQL not running or connection parameters are wrong; start MySQL and/or correct file "connect.inc.php"). Please read the documentation: http://lwt.sf.net');

@mysql_query("SET NAMES 'utf8'");

$err = @mysql_select_db($dbname);
if ($err == FALSE && mysql_errno() == 1049) runsql("CREATE DATABASE `" . $dbname . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci",'');

$err = @mysql_select_db($dbname);
if ($err == FALSE) die('DB select error (Cannot find database: "'. $dbname . '" or connection parameter $dbname is wrong; please create database and/or correct file: "connect.inc.php"). Hint: The database can be created by importing the file "dbinstall.sql" within phpMyAdmin. Please read the documentation: http://lwt.sf.net');   

// check/update db
check_update_db();

// -------------------------------------------------------------

?>