---
title: "Networks, Geography, and Gephi: Lots of Promise, but Lots of Work to be Done"
date: "2015-10-07"
categories: 
  - "maps"
  - "social-networks"
tags: 
  - "gephi"
  - "openlayers"
  - "social-networks"
---

This post will outline some of my efforts to bring social networks into dialog with geography. Although I have found some interesting plugins and hacks, the results still leave something to be desired.

[](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-1-40-46-pm.png)[![Screen Shot 2015-10-07 at 1.40.46 PM](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-1-40-46-pm.png?w=300)](http://awmc.unc.edu/awmc/applications/snagg_test/)To provide some background: From my dissertation I have a nice, [interactive map of all garrisons](http://awmc.unc.edu/awmc/applications/snagg_test/) (_phrourai_, in orange), and garrison commanders (_phrourarchoi_, in white) from all of Greek sources up to the mid second century C.E. This is all nicely georeferenced, linked to other projects such as [Pleiades](http://pleiades.stoa.org/) and [Pelagios](http://pelagios-project.blogspot.co.uk/), and serves its purpose pretty well. However, this provides the location and frequency of garrisons and commanders, and does not really show the social network that developed between commanders, monarchs, and communities. I could perhaps use a clustering strategy to create dynamic markers around specific points, but that seems to be a very unwieldy solution.

Strictly speaking, by modeling people (_phrourarchoi_, monarchs) with places and abstract communities I am moving beyond a [_social network_ and instead looking at an _information network_](https://www.cs.cornell.edu/home/kleinber/networks-book/networks-book-ch13.pdf), as I am interested in a number of different connections (social, geographic, ideological) that are not traditionally associated with social network analysis.

The first step to get all of my data into [Gephi](https://gephi.github.io/), assign different "types" to my nodes (in my case people, offices, places, _phrourarchoi_). I then created a network map, ran statistics, assigned the node size based on degree, and ran a _force atlas_ layout. At the same time I also color coded the network based on type. This is all pretty basic [Gephi](https://gephi.github.io/) use so far, and produced a perfectly serviceable network graph.

\[caption id="attachment\_186" align="aligncenter" width="300"\][![degree](https://ryanmatthewhorne.files.wordpress.com/2015/10/degree.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/degree.png) First graph. Pretty basic and serviceable.\[/caption\]

Now it was time to experiment with different types of ranking. [Betweenness centrality](https://en.wikipedia.org/wiki/Betweenness_centrality), or the measure of a node's influence, led to an interesting difference in graphs:

\[caption id="attachment\_187" align="aligncenter" width="300"\][![Untitled](https://ryanmatthewhorne.files.wordpress.com/2015/10/untitled.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/untitled.png) Betweenness Graph. Note the increased importance of individuals.\[/caption\]

However, this result is somewhat meaningless, as my graph covers a period from the 400s BCE to the 100s CE. Despite any of his wishes to the contrary, [Ptolemy VIII](https://en.wikipedia.org/wiki/Ptolemy_VIII_Physcon) did not live forever, yet he is the unquestioned central authority of this graph. All of the other Egyptian monarchs also score highly, underlining their importance in the communications and relationships between different _phrourarchoi._ This is an interesting yet hardly unsurprising finding - a good portion of the surviving data on _phroruarchoi_ originates from [Ptolemaic Egypt](https://en.wikipedia.org/wiki/Ptolemaic_Kingdom), which may inflate the relative importance of the dynasty in this kind of analysis. What this map does show is the enormous influence of individuals - most of whom were not _phrourarchoi_ themselves.

However, I am interested in garrisons as a sustained phenomena across several centuries, so I want to get back to the importance of location and geography on garrisons. In other words: Where are the most important locations for _phrourarchoi,_ and how do those relate to one another?

Running an _[Eigenvector Centrality](http://faculty.ucr.edu/~hanneman/nettext/C10_Centrality.html#Eigenvector)_ measurement produces a graph that somewhat mimics my original map, with physical locations, not people as the most significant authorities. This gives a better impression of what I am looking for - the centrality of a node relative to the whole network, which in my case privileges locations, which often serve as a bridge between different populations of nodes.

\[caption id="attachment\_189" align="aligncenter" width="300"\][![egienvector](https://ryanmatthewhorne.files.wordpress.com/2015/10/egienvector.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/egienvector.png) Eigenvector Centrality\[/caption\]

To me this is an interesting graph: It shows the importance of locations, while still highlighting important individuals. Now that I have this graph, I would love to place it on a map. I actually have coordinates for all of the locations, so a simple use of the [Gephi](https://gephi.github.io/) [GeoLayout](https://marketplace.gephi.org/plugin/geolayout/) plugin puts all of my identified places in a rough geographic layout.

[![Screen Shot 2015-10-07 at 12.48.40 PM](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-12-48-40-pm.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-12-48-40-pm.png)

From here I simply fixed the location of the places, then ran some other layouts to try and make a coherent graph of people and offices that did not have a specific geographic value.The results were generally less than satisfying. The individuals in my dataset are not assigned coordinates because it would make little sense to do so - some _phroruarchoi_ served in multiple locations, and almost all imperial _phrourarchoi_ served outside their place of origin, were buried somewhere else, possibly lived in yet another location, etc.

\[caption id="attachment\_192" align="alignleft" width="300"\][![geo](https://ryanmatthewhorne.files.wordpress.com/2015/10/geo.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/geo.png) Force Atlas combined with [GeoLayout](https://marketplace.gephi.org/plugin/geolayout/)\[/caption\]

\[caption id="attachment\_193" align="alignright" width="300"\][![circle](https://ryanmatthewhorne.files.wordpress.com/2015/10/circle.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/circle.png) Force atlas and Fruchterman-Reingold\[/caption\]

\[caption id="attachment\_195" align="aligncenter" width="300"\][![geo-attempr](https://ryanmatthewhorne.files.wordpress.com/2015/10/geo-attempr.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/geo-attempr.png) Adjusting the size of the nodes and running force atlas eventually produced  a result that looks more comprehensible, if a bit small.\[/caption\]

From this step, I thought I would try out some [Gephi](https://gephi.github.io/) plugins to push my data into a format I could drop onto a map. Only a very small percentage of my nodes actually contain geographic information, so the [ExportToEarth](https://marketplace.gephi.org/plugin/exporttoearth/) plugin was not going to help. My first attempt at pushing out a shapefile using [Export to SHP](https://marketplace.gephi.org/plugin/export-to-shp/) initially looked like a success in [QGIS](http://www.qgis.org/en/site/):

\[caption id="attachment\_196" align="aligncenter" width="300"\][![Screen Shot 2015-10-07 at 1.43.16 PM](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-1-43-16-pm.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-1-43-16-pm.png) This looks promising...\[/caption\]

So, I decided to throw in some background, and that is when the trouble started. [QGIS](http://www.qgis.org/en/site/) does a good job of transforming coordinates, but this was just messy (and not to mention wrong - there certainly were no _phrourarchoi_ in Antarctica!)

\[caption id="attachment\_197" align="aligncenter" width="300"\][![Screen Shot 2015-10-07 at 1.35.59 PM](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-1-35-59-pm.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-10-07-at-1-35-59-pm.png) Note how the nodes are now literally all over the map.\[/caption\]

So, what happened? If you do not have coordinates already explicitly assigned to your data, [Export to SHP](https://marketplace.gephi.org/plugin/export-to-shp/) actually does not use "geographic" coordinates, and instead uses, in the words of the plugin, "fake geography – that is the current position of the nodes in the Gephi layout". My thought that this position would line up with correct coordinates from[GeoLayout](https://marketplace.gephi.org/plugin/geolayout/) were false -[Export to SHP](https://marketplace.gephi.org/plugin/export-to-shp/) treats the middle of the map as an origin point (instead of using whatever geographic data is present), and as such it does not match with any projection in [QGIS](http://www.qgis.org/en/site/).

This is a bit of a let down. It seems that all of mapping plugins in [Gephi](https://gephi.github.io/) need for \*_**ALL**_\* of the nodes to have geographic information already baked in, or they will not export a geographically accurate map. This does make some sense, but it would be nice if you could use [GeoLayout](https://marketplace.gephi.org/plugin/geolayout/) to place nodes with actual geographic data, then use force atlas or some other layout to produce a graph, and finally use the location of those nodes as coordinates. In other words, the location of nodes on the graph that have no actual geographic data of their own are located relative to nodes that do have geographic data. I tried the [Sigmajs exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/), but the json object also does not use real coordinates, as seen in the fragment below ( _lng_ and _lat_ are the real-world coordinates, while _x_ and _y_ are used by [SigmaJS](http://sigmajs.org/)):

\[code language="javascript"\]

"label":"Priene/‘Lince’?", "x":-22.65546226501465, "y":32.66741943359375, "id":"Pl\_599905", "attributes":{ ... "lng":"27.297566084106442", "lat":"37.659724652604169", ...}, \[/code\]

So, is there a way around this?

Short of writing a new plugin to do so, it looks like [Gephi](https://gephi.github.io/) is simply missing the functionality of assigning geographic points to nodes that do not already have that information, then exporting that graph in a way that makes sense to mapping software. I could export an image and georeference that, but that will not provide the functionality I am looking for either.

What I would like is for a graph produced by [Gephi](https://gephi.github.io/) to use coordinates for nodes that have them, and make real world coordinates for nodes that do not. This map could then be placed on [Leaflet](http://leafletjs.com/) / [OpenLayers](http://openlayers.org/) / whatever map, providing a level of interaction beyond a static image. As it is impracticable to duplicate the functionality (especially the statistical tools and layouts) of [Gephi](https://gephi.github.io/) in a mapping application, this strikes me as something that would be very valuable to visualization and study.

My next idea is to see if [R](https://www.r-project.org/) has something close to what I want, which I will detail in a future post.
