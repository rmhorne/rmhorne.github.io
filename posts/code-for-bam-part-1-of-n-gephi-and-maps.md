---
title: "Code for BAM: Part 1 of N. Gephi and Maps"
date: "2015-09-28"
categories: 
  - "bam"
  - "code"
tags: 
  - "big-ancient-mediterranean"
  - "gephi"
  - "html"
  - "javascript"
  - "openlayers"
  - "social-networks"
---

This is the first in a series of posts where I will be detailing some of the code and development of [BAM](https://bigancientmediterranean.wordpress.com/). Some of these techniques may be old hat for some users or simple hacks, but they might be useful for anyone else who is trying to do similar work.

\[caption id="attachment\_97" align="alignright" width="300"\][![TBib-select](https://ryanmatthewhorne.files.wordpress.com/2015/09/tbib-select.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/09/tbib-select.png) Terra Biblica with both the social network graph and map displaying information on Jesus.\[/caption\]

In this post, I will detail how I got [Gephi](https://gephi.github.io/) data (produced by the [SigmaJs Exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/)) to communicate with an [OpenLayers 2](http://openlayers.org/two/) map. When a user clicks on any entity in the network graph the map panel will adjust to show the locations and frequency of that entity in geographic space. At the same time, any clicks on an entity name on the map (provided by a popup) will adjust the social network graph to highlight that entity. This code is built on javascript, PHP, and a PoistGIS backend. At some point in the future BAM may transition to OpenLayers 3, but for now we are sticking with 2 as it formed the basis for À-la-Carte, _Digital Strabo_, and other digital efforts that BAM builds upon and extends.

For a working demonstration of the final result, see [http://awmc.unc.edu/awmc/applications/bam/luke/](http://awmc.unc.edu/awmc/applications/bam/luke/). All of the code mentioned in this post, and created for BAM, is available at: [https://github.com/Big-Ancient-Mediterranean/BAM](https://github.com/Big-Ancient-Mediterranean/BAM).

Step 1: **Get your data in order!**

Before attempting any of this, you need to ensure that the entities that you are using in [Gephi](https://gephi.github.io/) and the ones you have in your database have a consistent, unique ID. So, if Andrew has an id of 1234567 in [Gephi](https://gephi.github.io/), you need to associate 1234567 with different locations, texts, etc in your database that are also related to Andrew. Failure to do so will make it VERY difficult, if not impossible, to get all of the different components to talk to each other.

Next, you actually need to build your network in [Gephi](https://gephi.github.io/) and export it out. Building the network itself is beyond the scope of this post, but you need to install and familiarize yourself with the excellent [SigmaJs Exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/) created by Scott Hale at the [Oxford Internet Institute](http://www.oii.ox.ac.uk/). Essentially what we are doing is taking the output of the [SigmaJs Exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/), cutting it down, and making it communicate with a dynamic, interactive map on the same webpage.

[![directory](https://ryanmatthewhorne.files.wordpress.com/2015/09/directory1.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/09/directory1.png)After exporting your network using the [SigmaJs Exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/), you should have a directory structure that roughly looks like the screenshot to the right. You want to upload everything but _htaccess\_example_, _web.config_, and _index.html_ to your webserver.

We then need to add this network to an HTML file that already has a map. In our case, we are modifying the code behind _Strabo Online_ and _SNAGG_. I may detail how to create a map in another post, but there are plenty of resources online to get you going on a basic map.

We are going to mimic the functionality of the index.html file that we excluded in our own html file. First, we need to include the various javascript files and libraries used by the application:

\[code language="html"\]

<script src="js/jquery/jquery.min.js" type="text/javascript"></script> <script src="js/sigma/sigma.min.js" type="text/javascript" language="javascript"></script> <script src="js/sigma/sigma.parseJson.js" type="text/javascript" language="javascript"></script> <script src="js/fancybox/jquery.fancybox.pack.js" type="text/javascript" language="javascript"></script> <script src="js/main.js" type="text/javascript" language="javascript"></script>

<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox.css"/> <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" /> <link rel="stylesheet" media="screen and (max-height: 770px)" href="css/tablet.css" />

\[/code\]

Now we need to place some divs to hold the content from our social network. These can be styled at your leisure.

\[code language="html"\]

<div style="padding-left: 1%;padding-right: 1%;" id="socialNetContainer" class="socialNetContainer">

<div class="sigma-parent">

<div class="sigma-expand" id="sigma-canvas">

<div style="z-index:9994" id="attributepane">

<div class="text">

<div title="Close" class="left-close returntext">

<div class="c cf"> <span>Return to the full network</span> </div>

</div>

<div class="nodeattributes">

<div class="name"></div>

<div class="data"></div>

<div class="p">Connections:</div>

<div class="link">

<ul> </ul>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

\[/code\]

Now that we have all the functionality of the [SigmaJs Exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/) in our map, we need to make the components talk to each other. First, we need to identify what node is active on the sigma.js div, and use that information to select the appropriate data for our map. The function _nodeActive_ in SigmaJs identifies what / when a node is active - so we will extend this to pass that information to a variable (for a more detailed explanation on how to extend a javascript function, see [http://coreymaynard.com/blog/extending-a-javascript-function/](http://coreymaynard.com/blog/extending-a-javascript-function/)).

We are also going to create a separate function to deal with adjusting the map itself, called tBibPersonConnections, which will be called in our new, extended function:

\[code language="javascript"\] (function() { //first copy the old function in the new one var old\_nodeActive = nodeActive;

//new function with the same name as the old one - this overrides the old function nodeActive = function() {

//we are going to build the map from the person\_id that is called from the node // this is a separate function that will be explained below tBibPersonConnections(arguments\[0\], tBibPeoplelayer); activePerson = arguments\[0\];

// Calls the original function\\ var result = old\_nodeActive.apply(this, arguments);

// now return the result return result; } })(); \[/code\]

tBibPersonConnections is where the work really happens. Lets examine this function slowly.

\[code language="javascript"\]

function tBibPersonConnections(personNameChoice, tBibPeoplelayer) { var dataStringForFeature ='pid=' +personNameChoice +'&amp;amp;amp;amp;amp;amp;start=0'; tBibPeoplelayer.destroyFeatures(); tBibfeaturesOnMap =\[\];

$.ajax({ dataType: "json", type:'GET', data:dataStringForFeature, url:'tbib\_mapmaker.php', success:function(dataJson) { for (var i = 0; i &amp;amp;amp;amp;amp;lt; dataJson.features.length; i++){ var untransformed\_feature = geojson\_format.read(dataJson, "FeatureCollection"); //for some reason this is going into an array. Going to hardcode for now for (var j = 0; j &amp;amp;amp;amp;amp;lt; dataJson.features.length; j++){ if (tBibfeaturesOnMap.indexOf(untransformed\_feature\[j\].attributes.pid) &amp;amp;amp;amp;amp;lt; 0){ tBibPeoplelayer.addFeatures(untransformed\_feature\[j\]); tBibfeaturesOnMap.push(untransformed\_feature\[j\].attributes.pid); } } tBibPeoplelayer.refresh({force:true}); } }, error: function (xhr, ajaxOptions, thrownError) { alert(xhr.responseText); } });

} \[/code\]

The function takes the ID of the person selected and layer that houses all of the feature information as arguments.

The first thing we do is create parameters for the PHP file that will return all of the place / feature information that is associated with an individual person. Do not worry about the "start" parameter for now, as it is only used when resetting the map to an initial state. The lines

tBibPeoplelayer.destroyFeatures();
tBibfeaturesOnMap =\[\];

first clear the map layer of all features, and then sets up an array to hold all of the new features that we will be adding to the map.

The AJAX call to tbib\_mapmaker.php actually queries our database, and returns each feature that is associated with an individual, the number of times the individual is mentioned with the feature, and the geographic location of the feature. While the actual sql calls are specific to this application / database, I will show what we are doing for combining Pleiades data, BAM data, and the map:

\[code language="PHP"\] $query = "select pplaces.title, count(pplaces.title), max (pplaces.id) as pleaides\_id, ST\_AsGeoJSON(ST\_Transform(max(pplaces.the\_geom), 3857)) as geom from pplaces JOIN tbib\_pleiades ON pplaces.id = tbib\_pleiades.pleiades\_id JOIN tbib\_network ON tbib\_pleiades.verse = tbib\_network.reference where character\_1 = '$pidParam' or character\_2 = '$pidParam' GROUP BY pplaces.title"; \[/code\]

We are interested in every occurrence of an individual, so we do not care if the person is the target or the source. Our tbib\_network table is _**exactly**_ the same as the table used to build our Gephi network, and all people are assigned a unique ID that remains consistent across tables.

At the end of the .php file, all of the results are returned in json format:

\[code language="PHP"\] //make a geojson object while($row =pg\_fetch\_assoc($qry\_result)){ //resize for map $sizeForMap = (($row\[count\] / 10) + 1);

//arrange for map $arr\[\] = array( "type" => "Feature", "geometry" => json\_decode($row\[geom\]), "properties" => array( "title" =>$row\[title\], "count" =>$sizeForMap, "pid" => $row\[pleaides\_id\] ), ); } //encode into geojson $geojson = '{"type":"FeatureCollection","features":'.json\_encode($arr).'}'; echo $geojson; ?> \[/code\]

In the future, this database work will be mirrored by static json files, to allow for the easy export / import of BAM material.

When the PHP file returns a json string, the function then pulls it apart, creates new OpenLayers features, and then adds them to the map:

\[code language="javascript"\] success:function(dataJson) { for (var i = 0; i < dataJson.features.length; i++){ var untransformed\_feature = geojson\_format.read(dataJson, "FeatureCollection"); for (var j = 0; j < dataJson.features.length; j++){ if (tBibfeaturesOnMap.indexOf(untransformed\_feature\[j\].attributes.pid) < 0){ tBibPeoplelayer.addFeatures(untransformed\_feature\[j\]); tBibfeaturesOnMap.push(untransformed\_feature\[j\].attributes.pid); } } tBibPeoplelayer.refresh({force:true}); } }, \[/code\]

The result is a layer that changes depending on what person is clicked.

\[caption id="attachment\_131" align="alignright" width="300"\][![A user selected popup](https://ryanmatthewhorne.files.wordpress.com/2015/09/selected-location.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/09/selected-location.png) A user selected popup\[/caption\]

That is great for changing the map, but what about changing the nodes on the network graph for when an individual is selected on the map?

As we are displaying people names, not ID as clickable information in our popups, we need a way to translate the names to the IDs used by SigmaJs. This is simply a trivial php script that looks up an ID from a name table. Once the ID is returned, we simply activate the node with a call to the _nodeActive_ function that we extended earlier and to our tBibPersonConnections function.

First, however, we have to listen for the event where the popup on the map is clicked:

\[code language="javascript"\]

//this is the popup listner

$('#popupSnagTable tbody').on( 'click', 'td', function () { //now to start stripping out to what we need var columnName = $('#popupSnagTable thead tr th').eq($(this).index()).html().trim(); if (columnName == 'Reference')

{ var ActiveRef = $(this).html().trim(); ActiveRef = ActiveRef.replace('Lk ',''); var ActiveRefSpilt = ActiveRef.split(":"); activeChapter = ActiveRefSpilt\[0\]; activeVerse = ActiveRefSpilt\[1\]; getPerseusText($(this).html().trim(), 0); } //if the user clicks on a name, then we use this to make an ajax call if ((columnName == 'Entity 1') || (columnName == 'Entity 2')){ var personNameChoice = $(this).html().trim();

var dataString = 'pid='+personNameChoice;

$.ajax( { type:'GET', data:dataString, url:'bamIdFromNum.php', success:function(data2)

{

//from the sigma.js gephi instance

nodeActive(data2);

//now to add all of the places the entity is on the map. Searching by ID

tBibPersonConnections(data2, tBibPeoplelayer);

}

});

\[/code\]

That is all there is to it - just a few listeners and a variable or two. There may be more efficient ways of doing this, but all the components are talking to each other!
