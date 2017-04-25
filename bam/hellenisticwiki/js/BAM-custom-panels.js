//BAM-custom-panels.js
//holds all of the panels for the application. These are HIGHLY variable for each individual app




//the attribute box holds attributes for the map selection 
var attributeBoxHtml = '<div id="attributeBox" class="nonMapOverlay"> <div id="attributeBoxClose" class="popupCloseCarte">x</div> <div id="attributeBoxContents"></div> </div>';
$( "#panelHolder" ).append(attributeBoxHtml);
//this box holds the "popup" of the layer
overlayPanelsList.attributeBox = 'overlay';


var aboutBoxHtml = '<div id="infoBox" class="nonMapOverlay"> <b> Application Information </b>';
    aboutBoxHtml = aboutBoxHtml + '<div id="infoBoxClose" class="popupCloseCarte">x</div> <hr />';
    aboutBoxHtml = aboutBoxHtml + '<div id="infoBoxContent">';
	aboutBoxHtml = aboutBoxHtml + 'The <i><b>"Hellenistic" Royal Relationships In Wikipedia</i></b> project is a representation of royal familial relationships of the Hellenistic (as well as limited Archaic, Classical, and Roman) era in the Mediterranean World as offered by Wikipedia.';
    aboutBoxHtml = aboutBoxHtml + ' The data was gathered by hand from Wikipedia entries, then imported into Gephi to construct networks and run statistics. The network was then placed into the BAM platform to create visualizations and user interactions.';
    aboutBoxHtml = aboutBoxHtml + ' This is not ment to be a definitive prosopography - there are controversies and issues with the data, as well as possible transcription errors. Rather this is intended to show a "state of the field" in';
    aboutBoxHtml = aboutBoxHtml + ' Wikipedia\'s data, and to serve as a starting point for creating more detailed prosopographies, visualizations, and software.';
   	aboutBoxHtml = aboutBoxHtml + ' <br /><br />This project and the BAM software framework is created, built, and maintained by <a href="https://rmhorne.org/" target="_blank">Ryan Horne</a>.';
   	aboutBoxHtml = aboutBoxHtml +' <br /><br /><div>Some icons made by <a href="http://www.flaticon.com/authors/sarfraz-shoukat" title="Sarfraz Shoukat">Sarfraz Shoukat</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>';
    aboutBoxHtml = aboutBoxHtml + '<span class="bottomContainer">';
    aboutBoxHtml = aboutBoxHtml + '<a href="https://bigancientmediterranean.wordpress.com/" target="_blank"><img src="../images/BAM-icon.png" alt="BAM Logo" style="width:28px;height:28px;"></a>&nbsp;&nbsp;Built with the <b><a href=" https://bigancientmediterranean.wordpress.com/" target="_blank">Big Ancient Mediterranean </a></b>framework.';
    aboutBoxHtml = aboutBoxHtml + '<br /> <a href="https://github.com/Big-Ancient-Mediterranean" target="_blank"><img src="../images/GitHub-Mark-32px.png" alt="GitHub Logo" style="width:28px;height:28px;"></a>&nbsp;&nbsp;Get map data and code from our <b><a href="https://github.com/AWMC" target="_blank">GitHub </a></b>page.';
    aboutBoxHtml = aboutBoxHtml + '<br /></span></div> </div>';

//add to the panel holder
$( "#panelHolder" ).append(aboutBoxHtml);

//add to the list so we can perform functions on it
overlayPanelsList.infoBox = 'overlay';
