<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  	<title>Glenn Thompson - Homepage</title>
  	 <link rel="stylesheet" type="text/css" href="glenn.css" media="all" />
  	 
  	 
</head>
<body>


	<div id="content"> 
		
	<!-- 
	Available styles
	<h2>, <h3>, <strong>, <a>
	-->
	
		<table align="center">
		 <tr>

			<td valign="center" align="left">

				
				<?php
					include("topmenu.html");
				?>
              			<div id="portrait"><img src="images/glenn.png" alt="Glenn Thompson" align="right" /></div>      		
                  		<h2>Contact details</h2>

                  		<p><img src="http://137.229.32.80/images/icon_phone.gif" alt="*" width="27" height="27" hspace="2" />Telephone extension: 7424</p>
                  		<p><img src="images/icon_email.gif" alt="*" width="27" height="27" hspace="2" />Email address: <a href="mailto:glenn@giseis.alaska.edu">glenn@giseis.alaska.edu</a></p>
                  		<p><img src="images/icon_person.gif" alt="*" width="27" height="27" hspace="2" />Room: 307a</p>
				<h2 id="research">Background</h2> <p>Took a Joint Honours BSc in Theoretical Physics and Mathematics at St. Andrews, an MSc in Geophysics at Durham and then a PhD in Volcano-seismology at Leeds. First came to the <a href="http://gi.alaska.edu">Geophysical Institute</a> as an <a href="http://www.avo.alaska.edu">AVO</a> postdoc in 1998, then worked as the Seismologist and Deputy Director of the <a href="http://www.mvo.ms">Montserrat Volcano Observatory</a> for many years before returning to Alaska in 2006.</p>
				<h2 id="research">Research and Development</h2> <p> His main role at AVO is developing seismic monitoring software for a variety of different purposes and clients. Examples include systems for tracking volcanic tremor, earthquake swarms and the trajectory of pyroclastic flows in near-real-time; real-time notification of M&gt;5 earthquakes to emergency managers, and various web-based visualization tools to aid real-time monitoring.<br/>
				A secondary role is research. Topics of interest include:</p>                  
				<ul>
				<li>Banded seismicity at the Soufriere Hills Volcano, 1996-2006, and its relation to dome collapses, explosions and extrusion rate.</li>
				<li>Correlation between seismic amplitude/energy, dome collapse volume and ash column height.</li>
				<li>Volcanic tremor preceding the March 2009 eruptions of Redoubt Volcano.</li>
				<li>Very-long-period seismicity associated with the 2009 eruption of Redoubt Volcano, Alaska.</li>
				<li>Application of swarm and tremor alarm systems to Okmok 2008, Pavlof 2007, Augustine 2005/6, Shishaldin 1999</li>
				<li>Seismic network quality, based on simulation of smallest recordable magnitude</li>
				</ul>
				Other roles include student mentoring and beeper duty. However, during a volcanic crisis, monitoring becomes the primary duty.

			</td>

		</tr>
		 
		</table>
	</div> 

<p id="footer">
<SCRIPT language="JavaScript" type="text/javascript">
//  update - from http://rainbow.arch.scriptmania.com/scripts
<!--
function getLongDateString()
{	//method defined on class Date.
	//Returns a date string of the form: Day DD Month,YYYY
	//(e.g. Sunday 27 September, 1998)
	monthNames = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
dayNames = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	dayOfWeek = this.getDay();
	day = dayNames[dayOfWeek];
	dateOfMonth = this.getDate();
monthNo = this.getMonth();
	month = monthNames[monthNo];
year = this.getYear();
	if (year < 2000)
year = year + 1900;
dateStr = day+" "+dateOfMonth+" "+month+", "+year;
	return dateStr;
}
//register the  method in the class Date
Date.prototype.getLongDateString=getLongDateString;

function DocDate()
{ //return the document modification date (excl.time)
//as a string
	DateTimeStr = document.lastModified;
	secOffset = Date.parse(DateTimeStr);
	if (secOffset == 0 || secOffset == null) //Opera3.2
			 dateStr = "Unknown";
	else
	{
		aDate = new Date();
		aDate.setTime(secOffset);
		//use method defined above
		datestr = aDate.getLongDateString();
	}
	return dateStr;
}

document.write("Last Update: ");
document.writeln(DocDate(),"");
// -->
</script>
	</p>
</div>
</body>
</html>
