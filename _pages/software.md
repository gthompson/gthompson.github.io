---
layout: archive
title: "Software"
permalink: /software/
author_profile: true
---

* IceWeb is web-based extensions to the Iceworm (mixture of Antelope and Earthworm) monitoring system. It was the first application to generate rapidly-browseable multi-station spectrograms ([Pensive](https://volcanoes.usgs.gov/software/pensive/index.shtml) is a modern clone of that part), but also included digital helicorder plots, reduced displacement plots, and a tremor alarm system. I began IceWeb development in 1998 during my postdoc at the [Alaska Volcano Observatory](http://www.avo.alaska.edu) and the vision was to enable to Duty Seismologist to receive alerts of rapidly escalaing volcanic-seismicity, and be able to rapidly assess seismicity from home, without the need to drive to the observatory. Versions:
  * [original, full-featured 1998-2000 version](https://github.com/gthompson/IceWeb2000). This was live from 1998-2007.
  * [revised version](https://github.com/geoscience-community-codes/IceWeb). This was live from 2008-2020.
  * [Python version](https://github.com/gthompson/icewebPy) *under development*
* [GISMO](https://geoscience-community-codes.github.io/GISMO/) is an object-oriented seismic data analysis toolbox for MATLAB. It can also be used to convert seismic data formats. GISMO development has stagnated since [ObsPy](https://docs.obspy.org) became available. 
* [CWAKE](https://github.com/gthompson/cwake) is the *Common Workspace for AlasKan Earthquakes*. It was developed so that the [Alaska Volcano Observatory](http://www.avo.alaska.edu), [Alaska Earthquake Center](http://earthquake.alaska.edu), and researchers using seismic data from Alaska would have access to the same velocity models and default location parameters in the [Antelope environmental monitoring system](http://www.brtt.com), so that hypocenters and magnitudes can be directly compared. 
* [AVOSEIS](https://github.com/gthompson/AVOSEIS) are database-driven, seismic monitoring tools I developed while working for the [Alaska Volcano Observatory](http://www.avo.alaska.edu) at the [University of Alaska Fairbanks Geophysical Institute](https://www.gi.alaska.edu) from 11/2008-09/2012. Included are tools to sync Earthworm and AQMS with Antelope, to make maps of volcanic seismicity, to detect swarms and send alarms, plot rates of earthquake detections and events, split an Antelope database into day volumes, rebroadcast database records as parameter files on an ORB, summarize volcanic seismicity at all Alaskan volcanoes as a *sausage plot* or *shower curtain*, and to monitoring state-of-health corresponding to IceWeb spectrograms. Some of these have their own repositories:
  * [VOLC2](https://github.com/giseislab/VOLC2)
  * [Sausage Plot](https://github.com/giseislab/websausageplot)
* [XPick mimic](https://github.com/gthompson/seismicity-review-project) was an attempt to mimic XPick as closely as possible with dbpick to provide a viable alternative while AVO was considering migration to AQMS/Jiggle.

*Software developed at during PhD at University of Leeds, and while employed Montserrat Volcano Observatory, British Geological Survey, Alaska Earthquake Information Center, and University of South Florida not yet added.*
