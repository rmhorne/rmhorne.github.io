<?php
   $PageTitle="ICLAW: Iowa Canon of Latin Authors and Works";
   $NavBarBrand ="ICLAW: Iowa Canon of Latin Authors and Works";
   $NavBarImageLink='<a href="http://www.lib.uiowa.edu/bam/" target="_blank">';
   //check to see if there are any parameters for the application
   if (isset($_GET['pidInput'])) {
 $zoomToFeature = $_GET['pidInput'];
}
  //these files contain all of the needed includes for a basic map with a database
   include_once('../common-files/includes/BAM-header.php');
   include_once('../common-files/includes/BAM-map-headers.php');
   include_once('../common-files/includes/BAM-datatable-headers.php');

?>
    <!-- may need to put these in a different header file -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="../common-files/js/timeline/timeline.js"></script>
    <link rel="stylesheet" href="../common-files/js/timeline/timeline.css">

    <!-- this links to the basic setup file -->
    <script src="js/basic-map-setup.js"></script>

    <!-- this is your stylesheet for any custom changes -->
    <link rel="stylesheet" href="IowaCannon.css" type="text/css" />
    </head>

    <body>
        <?php 
		include_once('BAM-panels.php');
		include_once('BAM-layout.php'); 
		?>

        <script>
            //make all of the sub-windows draggable, as it is a nice thing to do
            $("#dataBox")
                .draggable()
                .resizable();
            $("#infoBox")
                .draggable()
                .resizable();

            $("#attributeBox")
                .draggable()
                .resizable();

            //accordion layout

            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function() {
                    this.classList.toggle("active");
                    this.nextElementSibling.classList.toggle("show");
                }
            }

            //variables
            var availableTags = [];
            var placesHolder = [];
            var masterPlacesList = [];
            var placesList = {};
            var placesIdForNoDuplicates = [];
            var peopleHolder = [];
            var placeTimeHolder = [];
            var popupTable;
            var main_locations;

            var outputStart = $('#timeStart');
            var outputEnd = $('#timeEnd');
            var isPaused = true;


            //create a popup which we will add things to later
            var popup = L.popup({});

            //style for the nodes. Can be expanded if needed
            var dynamicPlacesStyle = {
                radius: 3,
                fillColor: "white",
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.7
            };


            //change this link to you application - anything following it will be assed as a pleiades location
            var htmlLocation = 'http://link-to-your-application/';

            //functions
            function inArray(needle, haystack) {
                for (var i = 0; i < haystack.length; i++) {
                    if (haystack[i].properties.id == needle)

                    {
                        haystack[i].properties.count++;
                        return true;
                    }
                }
                return false;
            }

            //start with a loader box to cover all the other work
            $('#loadBox').show();

            //this hides the tooltip so it is not hovering around
            $('.city-name').hide();

            //restrict the interval to only numbers
            $('.numbersOnly').keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });

            //modify this as needed for your data
            var mainTable = '<table class="display" id="mainTable" border="1" cellpadding="2" cellspacing="4" summary="Feature List Table">';
            mainTable += "<thead>";
            mainTable += "<th>bamId</th>";
            mainTable += "<th>abbreviation</th>";
            mainTable += "<th>author_latin</th>";
            mainTable += "<th>author_english</th>";
            mainTable += "<th>century</th>";
            mainTable += "<th>date of birth</th>";
            mainTable += "<th>date of death</th>";
            mainTable += "<th>floruit</th>";
            mainTable += "<th>late antique</th>";
            mainTable += "<th>christian</th>";
            mainTable += "<th>location</th>";
            mainTable += "</tr></thead>";
            mainTable += "<tbody>";

            //now for the popuptable
            popupTable = '<table class="display" id="popupTable" border="1" cellpadding="2" cellspacing="4" summary="List of Authors and Works for Popups">';
            popupTable += "<thead>";
            popupTable += "<th>bamId</th>";
            popupTable += "<th>abbreviation</th>";
            popupTable += "<th>author_latin</th>";
            popupTable += "<th>author_english</th>";
            popupTable += "<th>century</th>";
            popupTable += "<th>location</th>";
            popupTable += "</tr></thead>";
            popupTable += "<tbody>";
            popupTable += "</tbody></table>";


            //using d3 here as it is used extensively in BAM. This could be replaced by another, lighter library, but I a, keeping it to allow for more modular access if other parts of BAM are needed

            d3.json(nodeEntityFileName, function(error, json) {

                var nodes = json.nodes.map(function(d) {

                    //now we make a .json structure of just the people

                    var personjsonFeature = {
                        'type': 'Person',
                        'properties': {
                            'bamId': d.attributes.bam_id,
                            'abbreviation': d.attributes.abbreviation,
                            'author_latin': d.attributes.author_latin,
                            'author_english': d.attributes.author_english,
                            'century': d.attributes.century,
                            'start': d.attributes.start,
                            'end': d.attributes.end,
                            'date_of_birth': d.attributes.date_of_birth,
                            'date_of_death': d.attributes.date_of_death,
                            'floruit': d.attributes.floruit,
                            'late_antique': d.attributes.late_antique,
                            'christian': d.attributes.christian,
                            'id': d.attributes.pleiades_id,
                            'location': d.attributes.title

                        }
                    };
                    peopleHolder.push(personjsonFeature);

                    //pull out the geo-data and make some geojson
                    //could have blank values, so checking and excluding those
                    if (isNaN(d.attributes.reprlong) == false && d.attributes.reprlong != '') {

                        //should check if it is holder first, increment if it is, add a new one if it is not
                        //the logic here is that each place should be represented by a single object. Needed data can be filtered
                        //this is where we pull out the data

                        var geojsonFeature = {
                            'type': 'Feature',
                            'properties': {
                                'id': d.attributes.pleiades_id,
                                'label': d.attributes.title,
                                //default values for just displaying things
                                'start': -100,
                                'end': 100,
                                'count': 1
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [d.attributes.reprlong, d.attributes.reprlat]
                            }
                        };
                        if (inArray(d.attributes.pleiades_id, placesHolder) == false) {

                            placesHolder.push(geojsonFeature);
                            placesList[d.id] = geojsonFeature;
                        }
                        //hold all the features
                        masterPlacesList.push(geojsonFeature);

                        //now that we have people and places, we need to assign the pleiades_id and dates those people use to a data structure, along with their BAM ID
                        var peoplePlacer = {
                            'id': d.attributes.pleiades_id,
                            'bamId': d.attributes.bam_id,
                            'start': d.attributes.start,
                            'end': d.attributes.end

                        };

                        placeTimeHolder.push(peoplePlacer);
                    }

                    //add to datatables
                    mainTable += "<tr>";
                    mainTable += "<td>";
                    mainTable += d.attributes.bam_id;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.abbreviation;
                    mainTable += "</td>";
                    //title
                    mainTable += "<td>";
                    mainTable += d.attributes.author_latin;
                    mainTable += "</td>";
                    //for the external data links
                    mainTable += "<td>";
                    mainTable += d.attributes.author_english;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.century;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.date_of_birth;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.date_of_death;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.floruit;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.late_antique;
                    mainTable += "</td>";

                    mainTable += "<td>";
                    mainTable += d.attributes.christian;
                    mainTable += "</td>";
                    
                    mainTable += "<td>";
                    mainTable += d.attributes.title;
                    mainTable += "</td>";

                    mainTable += "</tr>";
                })

                //as the layer we need depends on data to work, it has to fall under the loading mechanism for our csv, which is asynchronous. 

                main_locations = new L.geoJson(placesHolder, {
                    onEachFeature: onEachFeature,
                    pointToLayer: function(feature, latlng) {
                        return L.circleMarker(latlng, dynamicPlacesStyle);
                    }
                });


                //some styling due to count. Mess around and see what is visually arresting.
                main_locations.eachLayer(function(layer) {

                    if (layer.feature.properties.count <= 3) {
                        layer.setStyle({
                            radius: 3
                        });
                    } else if (layer.feature.properties.count <= 40) {
                        layer.setStyle({
                            radius: layer.feature.properties.count
                        });
                    } else {
                        layer.setStyle({
                            radius: 40
                        });
                    }
                });


                main_locations.addTo(map);
                map.fitBounds(main_locations.getBounds());

                //buttons here as some of the zoom functionality, etc depend on the data that we load
                L.easyButton('<span class="easySearch">&curren;</span>', function() {
                        map.fitBounds(main_locations.getBounds());
                    },
                    'Return To Original Zoom'
                ).addTo(map);

                L.easyPrint().addTo(map);

                //close off datatable
                mainTable += "</tbody></table>";
                $("#dataBoxContent").html(mainTable + '<br/>');


                $('#dataBoxCloseBox').mousedown(function() {
                    $('#dataBox').hide();
                });

                //more datatable configs
                var mainTableDataTable = $('#mainTable').DataTable({
                    dom: 'Bfrtip',
                    "bAutoWidth": false,
                    "sPaginationType": "full_numbers",
                    buttons: [
                        'copy', 'csv', 'print'
                    ]
                });

                $('#mainTable tbody').on('click', 'tr', function() {
                    var data = mainTableDataTable.row(this).data();
                    var result = $.grep(placesHolder, function(e) {
                        return e.properties.id === data[0];
                    });
                    //set zoom to choice
                    //right now hardwired for the first result, as UIDs are unique to this application. Could change this later.
                    map.setView([result[0].geometry.coordinates[1], result[0].geometry.coordinates[0]], 9);

                    //from http://stackoverflow.com/questions/31237459/how-to-open-leaflet-marker-popup-from-data-geojson-with-href
                    main_locations.eachLayer(function(feature) { //geojson is the object which have your data

                        //need to turn this into a singular function to capture all the data
                        if (feature.feature.properties.id === data[0]) { //insert the id in place of 'required-id'

                            makeBamPopup(feature.feature, map)
                            $("#leftTextPaneContent").html(htmlForBox);
                        }
                    });
                });

                $('#map').on('click', '.popupZoomButton', function(e) {
                    var result = $.grep(placesHolder, function(e2) {
                        return e2.properties.id === e.target.id;
                    });
                    //set zoom to choice
                    //right now hardwired for the first result, as UIDs are unique to this application. Could change this later.
                    map.setView([result[0].geometry.coordinates[1], result[0].geometry.coordinates[0]], 9);
                    //setLatLng
                    var newlatlng = L.latLng(result[0].geometry.coordinates[1], result[0].geometry.coordinates[0]);
                    popup.setLatLng(newlatlng);
                    popup.update();
                });


                //escape key closes the attribute pane and restores the network.
                $(document).keyup(function(e) {
                    if (e.keyCode == 27) { // escape key maps to keycode `27`
                        $('#attributeBox').hide();
                        $('#textpane').hide();
                        $('#infoBox').hide();
                        $('#dataBox').hide();
                        //close map popups
                        map.closePopup();
                    }
                });

                //code for going to pleiadesID as part of URL
                try {
                    var zoomPleiadesID = "<?php if (isset($_GET['pidInput'])) {$zoomToFeature = $_GET['pidInput'];echo $zoomToFeature;}?>";

                    if (zoomPleiadesID != 0) {
                        var result = $.grep(placesHolder, function(e) {
                            return e.properties.id === zoomPleiadesID;
                        });

                        //from http://stackoverflow.com/questions/31237459/how-to-open-leaflet-marker-popup-from-data-geojson-with-href
                        main_locations.eachLayer(function(feature) { //geojson is the object which have your data

                            //need to turn this into a singular function to capture all the data
                            if (feature.feature.properties.id === zoomPleiadesID) { //insert the id in place of 'required-id'
                                map.setView([result[0].geometry.coordinates[1], result[0].geometry.coordinates[0]], 9);
                                makeBamPopup(feature.feature, map);
                            }
                        });
                    }
                }
                //quick and dirty way to keep moving if there is an issue
                catch (e) {}
            });

            //functions down here                
            function onEachFeature(feature, layer) {
                //bind click
                layer.on('click', function(e) {
                    makeBamPopup(e.target.feature, map);
                });
                layer.on({
                    mouseover: function(e) {
                        $cityName.hide();
                        $cityName.text(e.target.feature.properties.label).show();
                    },
                    mouseout: function() {
                        $cityName.hide();
                    }
                });
            }
            //setup the map
                      var map = L.map('map', {
                    maxZoom: 10,
                    zoomControl: false
                }),
                $cityName = $('.city-name');

            map.setView([40.58058, 36.29883], 4);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiaXNhd255dSIsImEiOiJBWEh1dUZZIn0.SiiexWxHHESIegSmW8wedQ', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 10,
                id: 'isawnyu.map-knmctlkh',
                accessToken: 'pk.eyJ1IjoiaXNhd255dSIsImEiOiJBWEh1dUZZIn0.SiiexWxHHESIegSmW8wedQ'
            }).addTo(map);


            map.setView([40.58058, 36.29883], 4);

            L.control.scale().addTo(map);

            //tooltip for mouse over city names
            var tooltipSpan = document.getElementById('city-name');

            window.onmousemove = function(e) {
                var x = e.clientX;
                var y = e.clientY;
                tooltipSpan.style.top = (y + 20) + 'px';
                tooltipSpan.style.left = (x + 20) + 'px';
            };


            //now for the timeline
            var domElement = "#timeline";
            //should make this a config
            var sourceFile = "iclaw-base.csv";

            d3.csv(sourceFile, function(dataset) {
                timeline(domElement)
                    .data(dataset)
                    .band("naviBand", 0.23)
                    .tooltips("naviBand")
                    .xAxis("naviBand")
                    .brush("naviBand")
                    .redraw();
            });
            //remove the loading screen
            $('#loadBox').hide();

            //set default values before listeners
            $('#timeStart').val(-500);
            $('#timeEnd').val(1800);


            //listen for timeline changes and modify the map as needed
            $('#timeStart').on('input propertychange change keyup paste', function() {
                changeMapFromTimeLine(map, main_locations, $('#timeStart').val(), $('#timeEnd').val(), placesHolder, peopleHolder);
            });

            $('#timeEnd').on('input propertychange change keyup paste', function() {
                changeMapFromTimeLine(map, main_locations, $('#timeStart').val(), $('#timeEnd').val(), placesHolder, peopleHolder);
            });

            //navigation for closing, etc

            $('#infoButton').click(function() {
                $('#dataBox').hide();
                $('#attributeBox').hide();
                $('#infoBox').toggle();
            });

            $('#infoBoxCloseBox').mousedown(function() {
                $('#infoBox').hide();
            });


            $('#databaseButton').click(function() {
                $('#dataBox').toggle();
                $('#attributeBox').hide();
                $('#infoBox').hide();
            });


            $('#attributeCloseBox').mousedown(function() {
                $('#attributeBox').hide();
            });



            function changeMapFromTimeLine(map, inputLayer, start, end, placesHolder, peopleHolder) {

                placesHolderTemp = [];

                //remove all markers from the layer
                inputLayer.eachLayer(function(layer) {
                    inputLayer.removeLayer(layer);
                });

                map.removeLayer(inputLayer);

                //now need to parse the centuries into years. What we are going to do is take all of the people who are in the range, then get the pleiades IDs from them.
                for (var i = 0; i < peopleHolder.length; i++) {
                    peopleHolder[i].properties.count = '1';
                    var tempYearStart = peopleHolder[i].properties.start.split('/');
                    var tempYearEnd = peopleHolder[i].properties.end.split('/');
                    //cast the string as int. This took me a while to remember...
                    var yearStart = parseInt(tempYearStart[2]);
                    var yearEnd = parseInt(tempYearEnd[2]);

                    //now check to see if the item is within the year parameters
                    if ((yearStart >= start && yearStart <= end) || (yearStart <= start && yearEnd >= start)) {
                        if (inArray(peopleHolder[i].properties.id, placesHolderTemp) == false) {
                            // placesHolder.push(masterPlacesList[i]);
                            //now need to build the place from our data
                            for (var k = 0; k < placesHolder.length; k++) {
                                if (placesHolder[k].properties.id == peopleHolder[i].properties.id) {
                                    tempFeature = placesHolder[k];
                                    tempFeature.properties.count = 1;
                                    placesHolderTemp.push(tempFeature);
                                }
                            }
                        } else {
                            for (var k = 0; k < placesHolderTemp.length; k++) {
                                if (placesHolderTemp[k].properties.id == peopleHolder[i].properties.id) {
                                    placesHolderTemp[k].properties.count++;
                                }
                            }
                        }
                    }
                }
                //now add to layer
                for (var i = 0; i < placesHolderTemp.length; i++) {
                    inputLayer.addData(placesHolderTemp[i]);
                }
                //return to style - should probably make this a fucntion
                inputLayer.eachLayer(function(layer) {

                    if (layer.feature.properties.count <= 3) {
                        layer.setStyle({
                            radius: 3
                        });
                    } else if (layer.feature.properties.count <= 40) {
                        layer.setStyle({
                            radius: layer.feature.properties.count
                        });
                    } else {
                        layer.setStyle({
                            radius: 40
                        });
                    }
                });

                map.addLayer(inputLayer);
            }

            function buildInternalPopupPersonTable(placeId, personList, popupTableDataTable, start, end) {

                popupTableDataTable.clear();
                for (var i = 0; i < personList.length; i++) {

                    //if there is a person at the location, add that person to the table
                    if (personList[i].properties.id === placeId) {
                    
                                        var tempYearStart = personList[i].properties.start.split('/');
                    var tempYearEnd = personList[i].properties.end.split('/');
                    //cast the string as int. This took me a while to remember...
                    var yearStart = parseInt(tempYearStart[2]);
                    var yearEnd = parseInt(tempYearEnd[2]);

                    //now check to see if the item is within the year parameters
                    if ((yearStart >= start && yearStart <= end) || (yearStart <= start && yearEnd >= start)) {

                        popupTableDataTable.row.add({

                            "bamId": personList[i].properties.bamId,
                            "abbreviation": personList[i].properties.abbreviation,
                            "author_latin": personList[i].properties.author_latin,
                            "author_english": personList[i].properties.author_english,
                            "century": personList[i].properties.century,
                            "date_of_birth": personList[i].properties.date_of_birth,
                            "date_of_death": personList[i].properties.date_of_death,
                            "floruit": personList[i].properties.floruit,
                            "late_antique": personList[i].properties.late_antique,
                            "christian": personList[i].properties.christian,
                            "location": personList[i].properties.location

                        });
                        }
                    }
                }
            }

            //modify the information in the popup as needed
            function makeBamPopup(feature, map) {

                //get the data for the table

                var htmlForBox = '<center><table><tr>';
                var zoomPopupContent = '<td><div id="' + feature.properties.id + '" class="popupZoomButton popupBaseButton"></div></a></td>';
                var pleiadesPopupContent = '<td><a href="http://pleiades.stoa.org/places/' + feature.properties.id + '" target="_blank"><div id="wmsPleiades' + feature.properties.id + '" class="popupBaseButton popupPleiadesButton" title="View ' + feature.properties.label + ' as ' + feature.properties.id + ' at Pleiades"></div></a></td>';
                var pelagiosPopupContent = '<td><a href="http://pelagios.org/peripleo/pages/places/http%3A%2F%2Fpleiades.stoa.org%2Fplaces%2F' + feature.properties.id + '" target="_blank"><div id="wmsAwmc' + feature.properties.id + '" class="popupBaseButton popupPelagiosButton" title="View ' + feature.properties.label + ' at Pelagios"></div></a></td></table></center>';

                var titleForBox = '<h1><center>' + feature.properties.label + '</h1></center>' + htmlForBox;

                var authorPopupContent = '<br /><h1>Authors located at ' + feature.properties.label + '</h1>' + pleiadesPopupContent + pelagiosPopupContent + popupTable;

                var permalinkPopupContent = '';
                if (feature.properties.id != 0) {
                    permalinkPopupContent = '<br /><a href="' + htmlLocation + feature.properties.id + '" target="_blank">Permalink</a>';
                }
                //set here for popup external to map attribute-contents

                var attributeHtml = htmlForBox + authorPopupContent;


                var pleiadesJsonUrl = 'http://pleiades.stoa.org/places/' + feature.properties.id + '/json';

                $.getJSON(pleiadesJsonUrl, function(data) {
                    var descPopup = data.description;
                    var popupContent = titleForBox + "From Pleiades: " + descPopup + '<br />' + htmlForBox + zoomPopupContent + pleiadesPopupContent + pelagiosPopupContent
                    var popupLatLng = L.latLng(feature.geometry.coordinates[1], feature.geometry.coordinates[0]);

                    popup.setLatLng(popupLatLng);
                    popup.setContent(popupContent);
                    popup.update();
                    popup.openOn(map);

                });

                $("#attribute-contents").html(attributeHtml);
                $("#attributeBox").show();

                var popupTableDataTable = $('#popupTable').DataTable({
                    "columns": [{
                        "data": "bamId"
                    }, {
                        "data": "abbreviation"
                    }, {
                        "data": "author_latin"
                    }, {
                        "data": "author_english"
                    }, {
                        "data": "century"
                    },{
                        "data": "location"
                    }],
                    dom: 'Bfrtip',
                    "bAutoWidth": false,
                    "sPaginationType": "full_numbers",
                    "pageLength": 5,
                    buttons: [
                        'copy', 'csv', 'print'
                    ]
                });

                buildInternalPopupPersonTable(feature.properties.id, peopleHolder, popupTableDataTable, $('#timeStart').val(), $('#timeEnd').val());
                popupTableDataTable.draw();
            }


            //timeline play / pause. From http://stackoverflow.com/questions/21277900/javascript-pausing-setinterval

            var t = window.setInterval(function() {
                if (!isPaused) {
                    var timerStart = parseInt($('#timeStart').val());
                    var timerEnd = parseInt($('#timeEnd').val());

                    //these values can be changes to show different speeds and timeline animation effects
                    var difTime = Math.abs(timerStart - timerEnd) / 5;
                    var tempTimerStart = timerStart + difTime;
                    var tempTimerEnd = timerEnd + difTime;
                    $('#timeStart').val(tempTimerStart);
                    $('#timeEnd').val(tempTimerEnd);
                    //now fire the click to move our D3js Timeline
                    $("#timeLineFire").trigger("click");
                }
            }, 500);

            //with jquery
            $('#timeLinePause').on('click', function(e) {
                e.preventDefault();
                isPaused = true;
            });

            $('#timeLinePlay').on('click', function(e) {
                e.preventDefault();
                isPaused = false;
            });
        </script>
    </body>

    </html>