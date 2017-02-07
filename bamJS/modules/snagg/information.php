<?php
   $PageTitle="SNAGG: Social Networks and Ancient Greek Garrisons";
   $NavBarBrand ="SNAGG";
   include_once('BAM-header.php');
		?>
		<br />
<br />
<br />
<br />

This application draws heavily on the work of the Pleiades Project and the Ancient World Mapping Center, 
and interfaces with the wider linked data community through the Pelagios project. 
The background is based on a set of tiles derived from the Ancient World Mapping Center's 
efforts to digitize and enhance the coverage of the Barrington Atlas, The underlying data 
is available under a Creative Commons Attribution 4.0 International license, while the code 
is released under GPL v3.
<br />
<br />

	The mapping application is fully integrated with the larger ancient world linked data community 
	through the use of 5-star data and stable URI principles. The software is a custom application
	 which is built on OpenLayers and DataTables, with a PostGIS backend.
	  The map tiles are hosted by MapBox, with the support of ISAW at NYU.
	   Every location mentioned was cataloged and aligned
	    with Pleiades identifiers by hand, as transliterations, Greek text, 
	    and multiple locations with the same name created enormous difficulties 
	    with automated matching. Every phrourarchos was then placed in a database,
	     given a unique identifier, and then matched with a relevant location. 
	     In instances where a given location was not in Pleiades, 
	     the phrourarchos was placed in a nearby location or in a larger region
	      (i.e. if the location is not in Pleiades but is known to be within the confines of Judaea, 
	      the phrourarchos was matched to Judaea itself). All of these instances were flagged in the 
	      database, so as Pleaides expands its coverage the location data will be able to adjust automatically. 
	      The final data was matched with the coordinate information provided by 
	      Pleiades, then exported as a .kml file, to allow for quick access without unduly querying the database.
	      <br />	      <br />

	In addition to locating all garrisons (orange circles) and their commanders (white circles) by default, the map offers a visual representation of the density of garrisons as found in the source material. This marking quickly highlights fiercely contested areas, and shows the near ubiquitous presence of garrisons, especially in coastal communities. This marking also highlights how the source material offers little detail concerning phrourarchoi outside of Egypt, Greece, and Western Asia Minor.
	      <br />
	      <br />

	In addition to a searchable database, every location on the map is clickable. A click generates a popoup on the map which provides the type of location, the commanding officer, the name of the officer when known, and relevant citation information. The window also allows for further exploration by linking to Pleiades and the Pelagios network, which allows a user to browse coins, texts, and other resources provided by dozens of partner projects. By presenting a seamless, interactive map, this application provides a level of visualization, customization, and interaction which is otherwise impossible with traditional media. 
     

        <?php include_once('BAM-footer.php');
      ?>
