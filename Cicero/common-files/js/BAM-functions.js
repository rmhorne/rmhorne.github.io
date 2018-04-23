//BAM Functions
 
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
            
//from https://datatables.net/reference/type/row-selector and https://datatables.net/reference/api/row().remove()
            function deletedatarow(table, delValue) {

                var deleteRow = [];

                deleteRow.push(table.rows(function(idx, data, node) {
                    return data.id == delValue ?
                        true : false;
                }));
                for (var i = 0; i < deleteRow.length; i++) {
                    table.row(deleteRow[i]).remove()
                        .draw();
                }
            }
            
function changeMapFromTimeLine(map, inputLayer, start, end, placesHolder, peopleHolder) {

                placesHolderTemp = [];

                //remove all markers from the layer
                inputLayer.eachLayer(function(layer) {
                    inputLayer.removeLayer(layer);
                });

                map.removeLayer(inputLayer);
                for (var i = 0; i < placesHolder.length; i++) {
                    var yearStart = parseInt(placesHolder[i].properties.minDate);
                    var yearEnd = parseInt(placesHolder[i].properties.maxDate);
                    if ((yearStart >= start && yearStart <= end) || (yearStart <= start && yearEnd >= start)) {
                        if (inArray(placesHolder[i].properties.id, placesHolderTemp) == false) {

                            placesHolderTemp.push(placesHolder[i]);
                        }
                    }
                }

                for (var i = 0; i < placesHolderTemp.length; i++) {
                    inputLayer.addData(placesHolderTemp[i]);
                }

                map.addLayer(inputLayer);
            }
            
            
function timelineUpdateFromMap(timelineEmpty, places, domElement) {
                var properties = [];
                for (var i = 0; i < places.length; i++) {
                    //a bit of a hack until the timeline data format is locked down
                    places[i].properties.start = places[i].properties.minDate;
                    places[i].properties.end = places[i].properties.maxDate;
                    properties.push(places[i].properties);
                }
                timelineEmpty.empty();

                timelineContainer = timeline(domElement)
                    .data(properties)
                    .band("naviBand", .5)
                    .tooltips("naviBand")
                    .xAxis("naviBand")
                    .brush("naviBand")
                    .redraw();
            }
            
            
     //modify the information in the popup as needed
            function makeBamPopup(feature, map, popup) {

                //get the data for the table
           		 var popupTable;

                var htmlForBox = '<center><table><tr>';
                var zoomPopupContent = '<td><div id="' + feature.properties.id + '" class="popupZoomButton popupBaseButton"></div></a></td>';
                var pleiadesPopupContent = '<td><a href="http://pleiades.stoa.org/places/' + feature.properties.id + '" target="_blank"><div id="wmsPleiades' + feature.properties.id + '" class="popupBaseButton popupPleiadesButton" title="View ' + feature.properties.title + ' as ' + feature.properties.id + ' at Pleiades"></div></a></td>';
                var pelagiosPopupContent = '<td><a href="http://pelagios.org/peripleo/pages/places/http%3A%2F%2Fpleiades.stoa.org%2Fplaces%2F' + feature.properties.id + '" target="_blank"><div id="wmsAwmc' + feature.properties.id + '" class="popupBaseButton popupPelagiosButton" title="View ' + feature.properties.title + ' at Pelagios"></div></a></td>';
                var editPopupContent = '<td><div id="edit_' + feature.properties.id + '" class="popupEditButton popupBaseButton"></div></a></td>';
                var deletePopupContent = '<td><div id="delete_' + feature.properties.id + '" class="popupTrashButton popupBaseButton"></div></a></td>';
                var popupButtonsEnd = '</table></center>';
                var titleForBox = '<h1><center>' + feature.properties.title + '</center></h1><center><h2>' + feature.properties.featureTypes + '</h2></center>' + htmlForBox;
                var authorPopupContent = '<br /><h1>Authors located at ' + feature.properties.title + '</h1>' + pleiadesPopupContent + pelagiosPopupContent + popupTable;
                var permalinkPopupContent = '';
                if (feature.properties.id != 0) {
                    permalinkPopupContent = '<br /><a href="' + htmlLocation + feature.properties.id + '" target="_blank">Permalink</a>';
                }
                //set here for popup external to map attribute-contents
                var attributeHtml = htmlForBox + authorPopupContent;
                var pleiadesJsonUrl = 'http://pleiades.stoa.org/places/' + feature.properties.id + '/json';
                var popupContent = titleForBox + "From Pleiades: " + feature.properties.description + '<br /><br />' + htmlForBox + zoomPopupContent + pleiadesPopupContent + pelagiosPopupContent + editPopupContent + deletePopupContent + popupButtonsEnd;
                var popupLatLng = L.latLng(feature.geometry.coordinates[1], feature.geometry.coordinates[0]);

                popup.setLatLng(popupLatLng);
                popup.setContent(popupContent);
                popup.update();
                popup.openOn(map);
            }
            
       function makeCarteGeoJsonFeature(input) {

                var geoJsonFeature = {
                    'type': 'Feature',
                    'properties': {
                        'id': input.id,
                        'title': input.title,
                        'count': 1,
                        'description': input.description,
                        'featureTypes': input.featureTypes,
                        //these seem redundant, but are needed for the datatables add feature
                        'reprLong': input.reprLong,
                        'reprLat': input.reprLat
                    },
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [input.reprLong, input.reprLat]
                    }
                };
                //different data source formats. This may have to expand as more sources are brought online
                if (input.minDate) {
                    geoJsonFeature.properties.start = input.minDate;
                    geoJsonFeature.properties.end = input.maxDate;
                    geoJsonFeature.properties.minDate = input.minDate;
                    geoJsonFeature.properties.maxDate = input.maxDate;

                } else {
                    geoJsonFeature.properties.start = input.start;
                    geoJsonFeature.properties.end = input.end;
                    geoJsonFeature.properties.minDate = input.start;
                    geoJsonFeature.properties.maxDate = input.end;

                }
                //if there are no years there is a warning. Defaulting to the infamous 555
                if (geoJsonFeature.properties.start == null) {
                    geoJsonFeature.properties.start = '-555';
                    geoJsonFeature.properties.end = '555';
                    geoJsonFeature.properties.minDate = '-555';
                    geoJsonFeature.properties.maxDate = '555';
                }


                return geoJsonFeature;
            }


            function makeCarteGeoJsonFeatureWhole(input) {

                var geoJsonFeature = {
                    'type': 'Feature',
                    'properties': {
                        'id': input.properties.id,
                        'title': input.properties.title,
                        'count': 1,
                        'description': input.properties.description,
                        'featureTypes': input.properties.featureTypes,
                        //these seem redundant, but are needed for the datatables add feature
                        'reprLong': input.properties.reprLong,
                        'reprLat': input.properties.reprLat
                    },
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [input.properties.reprLong, input.properties.reprLat]
                    }
                };

                if (input.properties.featuretyp) {
                    geoJsonFeature.properties.featureTypes = input.properties.featuretyp;
                }

                if (input.properties.name) {
                    geoJsonFeature.properties.title = input.properties.name;
                }

                if (typeof geoJsonFeature.properties.featureTypes == "undefined") {
                    geoJsonFeature.properties.featureTypes = 'settlement';
                }


                if (input.geometry.coordinates[0]) {
                    geoJsonFeature.geometry.coordinates[0] = input.geometry.coordinates[0];
                    geoJsonFeature.properties.reprLong = input.geometry.coordinates[0];
                    geoJsonFeature.geometry.coordinates[1] = input.geometry.coordinates[1];
                    geoJsonFeature.properties.reprLat = input.geometry.coordinates[1];

                }
                if (typeof input.properties.custom_name !== "undefined") {
                    geoJsonFeature.properties.title = input.properties.custom_name;

                }
                if (typeof input.properties.basename !== "undefined") {

                    geoJsonFeature.properties.title = input.properties.basename;

                }

                if (typeof input.properties.pid !== "undefined") {

                    geoJsonFeature.properties.id = input.properties.pid;

                }


                if (typeof geoJsonFeature.properties.description == "undefined") {

                    geoJsonFeature.properties.description = "Legacy À-la-Carte Feature";

                }


                //different data source formats. This may have to expand as more sources are brought online
                if (input.properties.minDate) {
                    geoJsonFeature.properties.start = input.minDate;
                    geoJsonFeature.properties.end = input.maxDate;
                    geoJsonFeature.properties.minDate = input.minDate;
                    geoJsonFeature.properties.maxDate = input.maxDate;

                } else {
                    geoJsonFeature.properties.start = input.start;
                    geoJsonFeature.properties.end = input.end;
                    geoJsonFeature.properties.minDate = input.start;
                    geoJsonFeature.properties.maxDate = input.end;

                }
                //if there are no years there is a warning. Defaulting to the infamous 555
                if (geoJsonFeature.properties.start == null) {
                    geoJsonFeature.properties.start = '-555';
                    geoJsonFeature.properties.end = '555';
                    geoJsonFeature.properties.minDate = '-555';
                    geoJsonFeature.properties.maxDate = '555';
                }


                return geoJsonFeature;
            }            