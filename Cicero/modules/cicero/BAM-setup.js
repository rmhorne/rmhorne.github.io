//This file lists the different settings, etc for each project. This is so BAM can function as a modular famework

//sets the page title
var BamPageTitle = 'Cicero';

//sets the favicon if different
var BAMFavIocn = 'images/BAM-icon.png'

//sets the application title in the left navigation bar
var BamLeftPanelTitleHtml = '<b>Cicero\'s Letters</b>'

//sets the navbar image
var BamNavBarImageHtml = '<a href="https://www.lib.uiowa.edu/bam/" target="_blank"> <img src="images/BAM-icon.png" title="BAM Icon" alt="BAM Icon"></a>';

//sets the html for your choice of license. We STRONGLY recommend cc-by
 var BamApplicationLicenseHtml = '<a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a>';

//Set the attribute that you will base the counting on. This is for node size on the initial display
var BAMdataSizeAttribute = 'count';

//this variable adjusts what number is used to divide the count, as it may vary from project to project
var BAMSizeDivider = 1.6;

//sets the minimum size of the nodes on the map. This can be adjusted for different applications
var BAMNodeMinSize = 3;

//sets the maximum size of the nodes on the map. This can be adjusted for different applications
var BAMNodeMaxSize = 25;

//these setup the initial map view
var BAMMapSetViewX = 41.09591;

var BAMMapSetViewY = 10.7666;

var BAMMapSetViewZoom = 5;

//setup the base layer for the map. This may change for different applications depending on what era they are showing
var BAMMapBaseLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiaXNhd255dSIsImEiOiJBWEh1dUZZIn0.SiiexWxHHESIegSmW8wedQ', {
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        maxZoom: 10,
                        id: 'isawnyu.map-knmctlkh',
                        accessToken: 'pk.eyJ1IjoiaXNhd255dSIsImEiOiJBWEh1dUZZIn0.SiiexWxHHESIegSmW8wedQ'
                    });

//where is the main json file that holds our data?       
var BamMainDataLocation = 'data/place_network.json';

//for the attributes you want in the tooltip. The first Attribute is the "main" title
var BamToolTipTitle = 'title';
var BamToolTipAttributes = ['count', 'repLat'];

//sets the strok width on the connections
var BamStrokeWidth = 2;

// which attributes hold your geo-data?
var BamLatAttribute = 'repLat';

var BamLonAttribute = 'repLon';