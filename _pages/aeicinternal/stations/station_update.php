<?php
function selfURL() { $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; } 
function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }
function epoch2ymd($e) {
	$ymd = epoch2str($e, "%Y-%m-%d");
	if ($ymd == "1970-01-01") {
		$ymd = "";
	}
	return $ymd;
}

$_ENV{'ANTELOPE'} = "/opt/antelope/4.10";

if( !extension_loaded( "Datascope" ) ) { 
        dl( "Datascope.so" ) or die( "Failed to dynamically load Datascope.so" ) ; 
}
$cwd = getcwd();

$page_title = 'Station';
$css = array("style.css" );
$googlemaps = 0;
$js = array();

// Standard XHTML header
include('header.inc');

?>

<body>
<h1><center>Station Update/New</center></h1>
<p>Use this form for notifying the Seismic Data Manager of changes to existing stations, or new stations. An email is sent, notifying the Seismic Data Manager that changes need to be made to the AEIC "Master Stations Database".</p>

<hr/>
<h2>Station code</h2>
<p>Enter station code below. If you are updating an existing station, simply press "Load station metadata", modify the metadata, and then press "Email Metadata". If you are creating a new station, give the new station code directly, and a blank form will be presented. Alternatively, enter the station code of a similarly configured station here, press "Load station metadata", then change the station code by pressing "Set new station code". Then modify the metadata and press "Email Metadata". </p>

<?php

	$stationsdb = "/Seis/databases/stations/master_stations";

	# Handle all the different submit buttons
	$submit         = isset($_REQUEST['submit'])? $_REQUEST['submit'] : NULL;
	$numnewchannels = isset($_REQUEST['numnewchannels'])? $_REQUEST['numnewchannels'] : 0;
	$numnewchannels = ($submit == "Add new channel")? $numnewchannels + 1 : $numnewchannels;
	$numnewsensors  = isset($_REQUEST['numnewsensors'])? $_REQUEST['numnewsensors'] : 0;
	$numnewsensors  = ($submit == "Add new sensor")? $numnewsensors + 1 : $numnewsensors;
	$numnewaffils   = isset($_REQUEST['numnewaffils'])? $_REQUEST['numnewaffils'] : 0;
	$numnewaffils   = ($submit == "Add new network affiliation")? $numnewaffils + 1 : $numnewaffils;
	$netarray       = isset($_REQUEST['netarray'])? $_REQUEST['netarray'] : NULL;
	$view 		= isset($_REQUEST['view'])? $_REQUEST['view'] : "Show simple view";
	$simpleview	= ($view == "Show simple view")? 1 : 0;
	$load = 0;
	if (  isset($_REQUEST['load']) || isset($_REQUEST['reset']) ) {
		$numnewchannels = NULL;
		$numnewsensors  = NULL;
		$numnewaffils   = NULL;
		$netarray	= NULL;
#		list($ondate, $offdate, $lat, $lon, $elev, $staname, $statype, $refsta, $dnorth, $deast) = NULL;
#		list($chan[], $condate[], $coffdate[], $ctype[], $edepth[], $hang[], $vang[], $descrip[]) = NULL;
#		list($senchan[], $time[], $endtime[], $inid[], $insname[], $calper[], $instant[]) = NULL;
#		list($net[], $netname[]) = NULL;
#unset($chan);
#unset($condate);
		$load = 1;
	}
	if ( isset($_REQUEST['reset']) ) {
		unset($_REQUEST['station_code']);
	}

	$station_code = !isset($_REQUEST['station_code'])? NULL : $_REQUEST['station_code'];


	print "<form method=\"get\">\n"; 
	print "<table border=\"1\">\n";
	print "<tr><td>station_code </td><td><input name=\"station_code\" size=\"5\" value=\"$station_code\" type=\"text\"></td><td><input type=\"submit\" value=\"Load station metadata\" name=\"load\" /></td><td><input type=\"submit\" value=\"Set new station code\" name=\"new\" /></td><td><input type=\"submit\" value=\"reset\" name=\"reset\" /></td></tr>\n";
	print "</table>\n";
#	print "</form>\n";

	print "<hr/>\n";	

	# Now lets actually generated a populated form
	if ($station_code  && ($submit != "Email Metadata")) {
		
		print "<h2>Metadata for $station_code from $stationsdb</h2>\n";

		$dbptr = ds_dbopen($stationsdb, "r");

		print "<table border=\"2\"><tr><td>\n";


		########### Site Table ###############

		$numsites = isset($_REQUEST['numsites'])? $_REQUEST['numsites'] : 0;


		# There are two ways to get data: from database if 'Load Station Metadata' button was pressed (revised == NULL), or from submitted form 
		if ($load) {
			$dbptr_site= dblookup($dbptr, "", "site", "", "");
			$dbptr_site= dbsubset($dbptr_site, "sta == \"$station_code\"");
			if (dbquery($dbptr_site, "dbRECORD_COUNT") > 1) {
				$dbptr_site= dbsubset($dbptr_site, "offdate == NULL");
			}
			$numsites = dbquery($dbptr_site, "dbRECORD_COUNT");
			if ($numsites == 0) {
				list($ondate, $offdate, $lat, $lon, $elev, $staname, $statype, $refsta, $dnorth, $deast) = NULL;
			}
			else
			{
				$dbptr_site[3] = 0;
				list($ondate, $offdate, $lat, $lon, $elev, $staname, $statype, $refsta, $dnorth, $deast) = dbgetv($dbptr_site, "ondate", "offdate", "lat", "lon", "elev", "staname", "statype", "refsta", "dnorth", "deast");
	
			}
		}
		else
		{ # load parameters from $_REQUEST

			$ondate = $_REQUEST['ondate'];
			$offdate = $_REQUEST['offdate'];
			$lat = $_REQUEST['lat'];
			$lon = $_REQUEST['lon'];
			$elev = $_REQUEST['elev'];
			$staname = $_REQUEST['staname'];
			$statype = $_REQUEST['statype'];
			$refsta = $_REQUEST['refsta'];
			$dnorth = $_REQUEST['dnorth'];
			$deast = $_REQUEST['deast'];
		}
		?>	
		<table><tr>
		<td>
		<?php

		if ($numsites == 0) {
			print "<h2>site table (new site)</h2>\n";
			unset($_REQUEST['numdbchannels']);
			unset($_REQUEST['numdbsensors']);
			unset($_REQUEST['numdbaffils']);
		}
		else
		{
			print "<h2>site table</h2>\n";
		}


		# Take care of null values
		$ondate = epoch2str(str2epoch($ondate), "%Y-%m-%d");
		if ($offdate == -1 || $offdate == 0) { 
			$offdate = ""; 
		}
		else
		{
			$offdate = epoch2str(str2epoch($offdate), "%Y-%m-%d");
		}
		if ($refsta == "-") { $refsta = ""; }
		if ($statype == "-") { $statype = ""; }
		?>

		<!-- Now print out site record in an HTML table -->

		<table border="1">
		<tr><th>ondate</th><td><input name="ondate" size="10" value="<?php print $ondate; ?>" type="text"></td></tr>
		<tr><th>lat</th><td><input name="lat" size="8" value="<?php print $lat; ?>" type="text"></td></tr>
		<tr><th>lon</th><td><input name="lon" size="8" value="<?php print $lon; ?>" type="text"></td></tr>
		<tr><th>elev</th><td><input name="elev" size="8" value="<?php print $elev; ?>" type="text">km</td></tr>
		<tr><th>staname</th><td><input name="staname"  value="<?php print $staname; ?>" type="text"></td></tr>
		<?php
		if(!$simpleview) {
		?>
			<tr><th>statype</th><td><input name="statype" size="2" value="<?php print $statype; ?>" type="text"></td></tr> 
			<tr><th>offdate</th><td><input name="offdate" size="10" value="<?php print $offdate; ?>" type="text"></td></tr> 
		<?php
		}
		?>

		</table>

		</td>
		<?php
		if (!$simpleview) {
		?>
			<td>	
	
			<p>For arrays only:</p>
			<table border="1">
			<tr><th>refsta</th><td><input name="refsta" size="5" value="<?php print $refsta; ?>" type="text"></td></tr>
			<tr><th>dnorth</th><td><input name="dnorth" size="5" value="<?php print $dnorth; ?>" type="text"></td></tr>
			<tr><th>deast</th><td><input name="deast" size="5" value="<?php print $deast; ?>" type="text"></td></tr> 
			</table>
	
			</td>
		<?php
		}
		?>

		<td>
		<h2>Field Notes</h2>
		<p><textarea name="notes" value="<?php print $_REQUEST['notes']; ?>" cols="50" rows="10">Please enter other relevant info here...</textarea></p>
		</td>

		</tr></table>

		<!-- hidden variables -->

		<input type="hidden" name="numsites" value="<?php print $numsites; ?>">


		</td></tr>
		<?php
		######## end of site table ########




		########## Sensor Table ############
		print "<tr><td>\n";
		$numdbsensors = isset($_REQUEST['numdbsensors'])? $_REQUEST['numdbsensors'] : 0;
		if ($load && ($numsites > 0)) {
			$dbptr_sensor= dblookup($dbptr, "", "sensor", "", "");
			$dbptr_sensor= dbsubset($dbptr_sensor, "sta == \"$station_code\"");
			$dbptr_sensor= dbsubset($dbptr_sensor, "endtime == NULL");
			$dbptr_instrument = dblookup($dbptr, "", "instrument", "", "");
			$dbptr_sensor2= dbjoin($dbptr_sensor, $dbptr_instrument);
			#print "<p>" . implode(",", $dbptr_sensor2) . "</p>\n";
			if ($dbptr_sensor2[0] == -102) {
				$join = 0;
			}
			else
			{
				$join = 1;
				$dbptr_sensor = $dbptr_sensor2;
			}


			$numdbsensors = dbquery($dbptr_sensor, "dbRECORD_COUNT");

			for ($counter = 0; $counter < $numdbsensors; $counter++) {
				$dbptr_sensor[3] = $counter;
				
				list($senchan[$counter], $time[$counter], $endtime[$counter], $inid[$counter], $calper[$counter], $instant[$counter]) = dbgetv($dbptr_sensor, "chan", "time", "endtime", "inid", "calper", "instant");


				if ($join) {
					$insname[$counter] = dbgetv($dbptr_sensor, "insname");
				}
				else
				{
					$insname[$counter] = "(no join)";
				}
			

			}
		}
		elseif (!$load) 
		{ # load parameters from $_REQUEST
			$senchan = $_REQUEST['senchan'];
			$time = $_REQUEST['time'];
			$endtime = $_REQUEST['endtime'];
			$inid = $_REQUEST['inid'];
			$insname = $_REQUEST['insname'];
			$calper = $_REQUEST['calper'];
			$instant = $_REQUEST['instant'];
		}
		$numsensors = $numdbsensors + $numnewsensors;

		# display title
		print "<h2>sensor table ($numdbsensors sensors in database)</h2>\n";

		# Put each channel into an invisible table element
		print "<table><tr>\n";
		for ($counter = 0; $counter < $numsensors; $counter++) {

			# Take care of null values
			$time[$counter] = epoch2ymd($time[$counter]);
			if ($endtime[$counter] == 9999999999.999) { 
				$endtime[$counter] = ""; 
			}
			else
			{
				$endtime[$counter] = epoch2ymd($endtime[$counter]); 				
			}
			if ($instant[$counter] == "-") { $instant[$counter] = ""; }
			if ($calper[$counter] == -1) { $calper[$counter] = ""; }

			$thischan = $senchan[$counter];
			if ($thischan == 'BHZ' ||  $thischan == 'BNZ' || $thischan == 'EHZ' || $thischan == 'ENZ' ||  $thischan == 'HHZ' || $thischan == 'HNZ') {
  
				# Now print out each channel in an HTML table

				?>
				<!-- HTML -->

				<td>
				<table border="1">
				<tr><th>chan</th>   <td> <input name="senchan[<?php print $counter; ?>]" size="7" value="<?php print $senchan[$counter]; ?>"   type="text"></td></tr>
				<tr><th>time</th>   <td> <input name="time[<?php print $counter; ?>]"    size="10" value="<?php print $time[$counter]; ?>"      type="text"></td></tr>
				<tr><th>insname</th><td> <input name="insname[<?php print $counter; ?>]" size="16" value="<?php print $insname[$counter]; ?>"  type="text"></td></tr>
	
				<?php
				if (!$simpleview) {
				?>
					<tr><th>inid</th>   <td> <input name="inid[<?php print $counter; ?>]"    size="4" value="<?php print $inid[$counter]; ?>"      type="text"></td></tr>
					<tr><th>calper</th> <td> <input name="calper[<?php print $counter; ?>]"  size="5" value="<?php print $calper[$counter]; ?>"    type="text"></td></tr>
					<tr><th>instant</th><td> <input name="instant[<?php print $counter; ?>]" size="5" value="<?php print $instant[$counter]; ?>"   type="text"></td></tr>
					<tr><th>endtime</th><td> <input name="endtime[<?php print $counter; ?>]" size="10" value="<?php print $endtime[$counter]; ?>"   type="text"></td></tr>
	
				<?php
				}
				?>

				</table>
				<br/>
				</td>
			<?php
			}

		}
		print "</tr></table>\n";

		# hidden variables
		print "<input type=\"hidden\" name=\"numdbsensors\" value=\"$numdbsensors\">\n";
		print "<input type=\"hidden\" name=\"numnewsensors\" value=\"$numnewsensors\">\n";

		print "<input name=\"submit\" value=\"Add new sensor\" type=\"submit\">\n";



		print "</td></tr>\n";
		######## end of sensor table ##########





		
		######## Affiliation Table #########
		print "<tr><td>\n";

		$numdbaffils = isset($_REQUEST['numdbaffils'])? $_REQUEST['numdbaffils'] : 0;

		if ($load && ($numsites > 0)) {

			$dbptr_affil= dblookup($dbptr, "", "affiliation", "", "");
			#print "<p>" . implode(",", $dbptr_affil) . "</p>\n";
			$dbptr_affil= dbsubset($dbptr_affil, "sta == \"$station_code\"");
			$dbptr_network = dblookup($dbptr, "", "network", "", "");
			$dbptr_affil2= dbjoin($dbptr_affil, $dbptr_network);
			if ($dbptr_affil2[0] == -102) {
				$join = 0;
			}
			else
			{
				$join = 1;
				$dbptr_affil = $dbptr_affil2;

			}

			$numdbaffils = dbquery($dbptr_affil, "dbRECORD_COUNT");

			for ($counter = 0; $counter < $numdbaffils; $counter++) {
				$dbptr_affil[3] = $counter;
				$net[$counter] = dbgetv($dbptr_affil, "net");
				if ($join) {
					$netname[$counter] = dbgetv($dbptr_affil, "netname");
				}
				else
				{
					$netname[$counter] = "(no join)";
				}


			}
		}
		elseif (!$load) 
		{ # load parameters from $_REQUEST
			$net = $_REQUEST['net'];
			$netname = $_REQUEST['netname'];
		}


		$numaffils = $numdbaffils + $numnewaffils;

		# display title
		print "<h2>affiliation table ($numdbaffils network affiliations in database)</h2>\n";

?>
<!-- HTML -->
		<table border="1"><tr><th>net</th><th>network</th></tr>
<?php
		for ($counter = 0; $counter < $numaffils; $counter++) {



			# Now print out each network in an HTML table

?>
<!-- HTML -->
			<tr><td> <input name="net[<?php print $counter; ?>]"     size="7"  value="<?php print $net[$counter]; ?>"     type="text"></td>
			<td> <input name="netname[<?php print $counter; ?>]"     size="25"  value="<?php print $netname[$counter]; ?>"     type="text"></td></tr>


<?php

		}
		print "</table>\n";

		# hidden variables
		print "<input type=\"hidden\" name=\"numdbaffils\" value=\"$numdbaffils\">\n";
		print "<input type=\"hidden\" name=\"numnewaffils\" value=\"$numnewaffils\">\n";
		print "<input name=\"submit\" value=\"Add new network affiliation\" type=\"submit\">\n";

		print "</td></tr>\n";
		######### end of affiliation table ##########


		######## Sitechan Table ########
		$numdbchannels = isset($_REQUEST['numdbchannels'])? $_REQUEST['numdbchannels'] : 0;
		if ($load && ($numsites > 0)) {

			$dbptr_sitechan= dblookup($dbptr, "", "sitechan", "", "");
			$dbptr_sitechan= dbsubset($dbptr_sitechan, "sta == \"$station_code\"");
			$dbptr_sitechan= dbsubset($dbptr_sitechan, "offdate == NULL");
			$numdbchannels = dbquery($dbptr_sitechan, "dbRECORD_COUNT");
			for ($counter = 0; $counter < $numdbchannels; $counter++) {
				$dbptr_sitechan[3] = $counter;
				list($chan[$counter], $condate[$counter], $coffdate[$counter], $ctype[$counter], $edepth[$counter], $hang[$counter], $vang[$counter], $descrip[$counter]) = dbgetv($dbptr_sitechan, "chan", "ondate", "offdate", "ctype", "edepth", "hang", "vang", "descrip");
			}
		}
		elseif (!$load) 
		{ # load parameters from $_REQUEST
			$chan = $_REQUEST['chan'];
			$condate = $_REQUEST['condate'];
			$coffdate = $_REQUEST['coffdate'];
			$ctype = $_REQUEST['ctype'];
			$edepth = $_REQUEST['edepth'];
			$hang = $_REQUEST['hang'];
			$vang = $_REQUEST['vang'];
			$descrip = $_REQUEST['descrip'];
			$numdbchannels = $_REQUEST['numdbchannels'];
		}
		$numchannels = $numdbchannels + $numnewchannels;

		# display title
		if (!$simpleview) {
			print "<tr><td>\n";
			
			print "<h2>sitechan table ($numdbchannels channels in database)</h2>\n";
					
			# Put each channel into an invisible table element
			print "<table><tr>\n";
		}

		for ($counter = 0; $counter < $numchannels; $counter++) {

			# Take care of null values
			$condate[$counter] = epoch2ymd(str2epoch($condate[$counter]));
			if ($coffdate[$counter] == -1) { 
				$coffdate[$counter] = ""; 
			}
			else
			{
				$coffdate[$counter] = epoch2ymd(str2epoch($coffdate[$counter]));
			}
			if ($edepth[$counter] == -9.9999) { $edepth[$counter] = ""; }
			if ($ctype[$counter] == "-") { $ctype[$counter] = ""; }


			$thischan = $chan[$counter];
			if ($thischan == 'BHZ' ||  $thischan == 'BNZ' || $thischan == 'EHZ' || $thischan == 'ENZ' ||  $thischan == 'HHZ' || $thischan == 'HNZ') {

				?>

				<!-- HTML -->
		
				<td>
				<table border="1">
				<tr><th>chan</th>       <td> <input name="chan[<?php print $counter; ?>]"        size="7" value="<?php print $chan[$counter]; ?>"         type="text"></td></tr>
				<tr><th>ondate</th>    <td> <input name="condate[<?php print $counter; ?>]"     size="10" value="<?php print $condate[$counter]; ?>"      type="text"></td></tr>
				<tr><th>offdate</th>   <td> <input name="coffdate[<?php print $counter; ?>]"    size="10" value="<?php print $coffdate[$counter]; ?>"     type="text"></td></tr> 
				<tr><th>ctype</th>      <td> <input name="ctype[<?php print $counter; ?>]"       size="2" value="<?php print $ctype[$counter]; ?>"        type="text"></td></tr>
				<tr><th>edepth</th>     <td> <input name="edepth[<?php print $counter; ?>]"      size="6" value="<?php print $edepth[$counter]; ?>"       type="text"></td></tr>
				<tr><th>hang</th>       <td> <input name="hang[<?php print $counter; ?>]"        size="5" value="<?php print $hang[$counter]; ?>"         type="text"></td></tr>
				<tr><th>vang</th>       <td> <input name="vang[<?php print $counter; ?>]"        size="5" value="<?php print $vang[$counter]; ?>"         type="text"></td></tr>
				<tr><th>descrip</th>    <td> <input name="descrip[<?php print $counter; ?>]"     value="<?php print $descrip[$counter]; ?>"      type="text"></td></tr>
				</table>
				<br/>
				</td>
			<?php

			}
		}
		if (!$simpleview) {
			print "</tr></table>\n";
		}
	
		# hidden variables
		print "<input type=\"hidden\" name=\"numdbchannels\" value=\"$numdbchannels\">\n";
		print "<input type=\"hidden\" name=\"numnewchannels\" value=\"$numnewchannels\">\n";

		# button to add a new channel
		print "<input name=\"submit\" value=\"Add new channel\" type=\"submit\">\n";
		if (!$simpleview) {
			print "</td></tr>\n";
		}
		########## end of sitechan table ############




		# Close out the bordering table
		print "</table>\n";

		# End form
		if ($simpleview) {
			$view_option = "Show expanded view";
		}
		else
		{
			$view_option = "Show simple view";
		}
		print "<br/><input type=\"submit\" name=\"view\" value=\"$view_option\" /><input type=\"submit\" name=\"submit\" value=\"Email Metadata\" />\n";
		print "</form>\n";


	}

?>


<?php
	if ($submit == "Email Metadata") {
		$url = selfURL();
		$new_url = substr($url, 0, -22);
		$progname = $_SERVER['PHP_SELF'];
		$to = "glenn@giseis.alaska.edu";
		$subject = "$progname";
		$body = "$Message from $new_url\nClick on these to view submitted data";

		if (mail($to, $subject, $body)) {
			echo("<p>Message sent to $to</p>");
		} 
		else 
		{
			echo("<p>Message delivery to $to failed...</p>");
		}
	}

	
?>


<hr/>
<div><p style="font-size: 9pt">
<script language="Javascript" type="text/javascript">now = new Date;document.write(now.toUTCString());</script>
<br/><i>This page is maintained by Glenn Thompson</i>
</p>
</div>

	

</body>
</html>



