<html>
  <head>
     <title>dbcalibrate graphics</title>
     <?php
	// define global variables
	$pdfDir = "./calpdf";

	// function to return an array of all pdffiles
	function getAllPdfFiles()
	{
		global $pdfDir;
		return $allPdfFiles = glob("$pdfDir/*.pdf");
	}

	// function to generate a list of unique stations from a list of displayscal pdf Files
	function files2staList($pdfFiles)
	{

		foreach ($pdfFiles as $file) {
			$allStations[] = substr(basename($file), 0, 4);
		}
		//print "<br>".implode(", ", $allStations);
		return $uniqueStations = array_unique($allStations);	
	}

	// function to generate a list of unique channels from a list of displayscal pdf Files
	function files2chanList($pdfFiles)
	{
		foreach ($pdfFiles as $file) {
			array_push($allChannels, substr(basename($file), 4, 3));
		}
		$uniqueChannels = array_unique($allChannels);	
		return $uniqueChannels;
	}

	// function to subset the pdfFiles for a particular station 
	function files2staFiles($pdfFiles, $sta)
	{
		foreach ($pdfFiles as $file) {
			if ($sta == substr(basename($file), 0, 4)) {
				array_push($staPdfFiles, $file);
			}
		}
		return $staPdfFiles;
	}
    ?>


  </head>

  <body bgcolor="#FFFFFF" onload="document.myForm.stations.selectedIndex=0">
  <form action="#" name="myForm">
    <p>Select a station and channel, then press submit, and the PDF file for that autocalibration will be displayed.<br/>
    This a comparison between the first response in the dlcalwf for a particular station and channel, and the most recent one.</p>
    <?php
	// Now get some crucial list arrays
        $allPdfFiles = getAllPdfFiles();
	$stations = files2staList($allPdfFiles);

	if(isset($_REQUEST['reset'])) {
		$_REQUEST['station']="";
		$_REQUEST['channel']="";
		$station="";
		$channel="";
		$pdffile2show="";
	}


	if(isset($_REQUEST['station']) && isset($_REQUEST['channel'])) {
		$station = $_REQUEST['station'];
		$channel = $_REQUEST['channel'];
		$pdffile2show = $station.$channel."_compare.pdf";
	}

    ?>
    <form method="get">
    <table>
       <tr><td>
	    <select name="station">
		<?php
			if (isset($station)) {
				print "<option value=\"$station\">$station</option>\n";
			}
			else
			{
				print "<option value=\"\">Select station</option>\n";

			}

			foreach ($stations as $sta) {
				print "\t<option value=\"$sta\">$sta</option>\n\t";
			}

		?>
	    </select>
        </td></tr>
	<tr><td>
	    <select name="channel">
		<?php
			$channels = array("HNZ", "HNN", "HNE");
			if (isset($channel)) {
				print "<option value=\"$channel\">$channel</option>\n";
			}
			else
			{
				print "<option value=\"\">Select channel</option>\n";

			}

			foreach ($channels as $chan) {
				print "\t<option value=\"$chan\">$chan</option>\n\t";
			}
		?>

	    </select>
       </td></tr>
	<tr><td>
		<input type="submit" value="submit">
		<input type="reset" value="reset">

	</td></tr>
	</form>
    </table>
	<?php
		if(isset($station) && isset($channel) && isset($pdffile2show)){ // && file_exists("/usr/local/mosaic/AEIC/internal/stations/calpdf/$pdffile2show") ) {
			print "<i><small>...PDF file $pdffile2show is loading...</small></i><br/>\n";
			print "<small>To display this file from your Solaris command prompt type:</br>&nbsp;&nbsp;acroread /usr/local/mosaic/AEIC/internal/stations/calpdf/$pdffile2show<br/>\n";

			print "<iframe SRC=\"calpdf/$pdffile2show\" width=\"100%\" height=\"100%\" frameborder=\"0\">If you can read this your browser does not support iframes.</iframe>";
		}
	?>	


  </body>
</html>

