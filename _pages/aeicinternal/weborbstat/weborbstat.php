<?php
	putenv( "ANTELOPE=/opt/antelope/4.9" );
	$_ENV{'ANTELOPE'} = "/opt/antelope/4.9";

	set_include_path( get_include_path() .
			  PATH_SEPARATOR . 
			  $_ENV{'ANTELOPE'} . "/data/www" .
			  PATH_SEPARATOR . 
			  $_ENV{'ANTELOPE'} . "/data/php" );
?>
<?php
if( !extension_loaded( "Datascope" ) ) {
	dl( "Datascope.so" ) or die( "Failed to dynamicaly load Datascope.so" ) ;
}
if( !extension_loaded( "Orb" ) ) {
	dl( "Orb.so" ) or die( "Failed to dynamically load Orb.so" ) ;
}

$pf = "weborbstat" ;

# pfupdate( $pf ) ;

$this_orb = pfget( $pf, 'orbname' ) ;
$select_regex = pfget( $pf, 'select_regex' ) ;
$header_settings = pfget( $pf, 'header_settings' ) ;
$body_settings = pfget( $pf, 'body_settings' ) ;
$data_latency_levels = pfget( $pf, 'data_latency_levels' ) ;

$open_orb = orbopen( $this_orb, "r" ) ;
$num_sources = orbselect( $open_orb, $select_regex ) ;
$orb_data = pforbstat( $open_orb, PFORBSTAT_SOURCES ) ;

#----------------
# LOCAL FUNCTIONS
#----------------

function _processConditions( $latency ) {
	global $data_latency_levels ;
	$number = count( $data_latency_levels ) ; 
	$default = $data_latency_levels[$number-1] ; #default to last value
	$background_color = $default['background_color'] ;
	$text_color = $default['text_color'] ;
	$text_size = $default['text_size'] ;

	foreach( $data_latency_levels as $dl ) {
		if( $latency < $dl['max_secs'] ) {
			$background_color = $dl['background_color'] ;
			$text_color = $dl['text_color'] ;
			$text_size = $dl['text_size'] ;
			break ;
		}
	}
	return array( $background_color, $text_color, $text_size ) ;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title><?php echo $header_settings['project_title'] ?></title>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=ISO-8859-1" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="refresh" content="<?php echo $header_settings['refresh_rate_sec'] ?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $header_settings['css_file'] ?>" />
</head>
<body>
<h1><?php echo $body_settings['title'] ?></h1>

<div id="key">
<table>
	<caption><?php echo $body_settings['table_caption'] ?></caption>
	<tbody>
	<?php 
	for( $i=0;$i<count( $data_latency_levels );$i++ ) {
		?>
		<tr>
		<td class="block" style="background-color:<?php echo $data_latency_levels[$i]['background_color'] ?>;"></td>
		<td>x &lt; <?php echo $data_latency_levels[$i]['max_secs'] ?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
</div>

<div id="dl">
<table>
	<thead>
		<tr>
			<th>Station name</th>
			<th>Data latency (seconds)</th>
			<th>Nbytes</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach( $orb_data['sources'] as $key => $val ) {
		$parts = split_srcname( $key ) ;
		$sta = $parts['sta'] ;
		$latency = $val['latency_sec'] ;
		$bytes = $val['nbytes'] ;
		list( $status, $text, $size ) = _processConditions( $latency ) ;
		?>
		<tr>
		<td><?php echo $sta ?></td>
		<td style='background-color:<?php echo $status ?>;
			color:<?php echo $text ?>;
			font-size:<?php echo $size ?>;'>
			<?php echo round( $latency, 2 ) ?>
		</td>
		<td style='background-color:<?php echo $status ?>;color:<?php echo $text ?>;font-size:<?php echo $size ?>;'>
			<?php echo $bytes ?>
		</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>

</div>

<?php orbclose( $open_orb ) ?>

</body>
</html>
