//network-no-map.js
//this js file gives BAM functionality to display a Gephi network without a mapping overlay - useful if you just want to show SNA
//
//stores a list of nodes
var nodeList = [];

//create a blank connections table that we will add to and modify later
var connectionsSearchTable = $('#right-side-text-connected-list').DataTable({
    "bLengthChange": false,
    "pageLength": 5,
    "columns": [{
            "data": "sourceTitle",
            "title": "Source",
            "width": "33%"
        },
        {
            "data": "sourceIndex",
            "visible": false
        },
        {
            "data": "connectionType",
            "title": "Relationship",
            "width": "33%"
        },
        {
            "data": "targetTitle",
            "title": "Target",
            "width": "33%"
        },
        {
            "data": "targetIndex",
            "visible": false
        }
    ]
});

//centered node
var centeredNode;

//Toggle stores whether the highlighting is on
var toggle = 0;

// zoom from https://jsfiddle.net/skgktrcu/
var zoom = d3.zoom()
    .scaleExtent([.2, 10])
    .on("zoom", zoomed);

var chartDiv = document.getElementById("d3MainChart");

// Extract the width and height that was computed by CSS.
var width = chartDiv.clientWidth;
var height = chartDiv.clientHeight;

var svg = d3.select(chartDiv).append("svg")
    .attr("width", width)
    .attr("height", height)
    .call(zoom)
    .append("g");

var color = d3.scaleOrdinal(d3.schemeCategory20);

var div = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0);

//some of the options from https://bl.ocks.org/syntagmatic/954b31aa8b8beb91b30ccb0c9e57f6ce

var simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) {
        return d.id;
    }))
    .force("collide", d3.forceCollide(function(d) {
        return d.size / 2
    }).iterations(2))
    .force("charge", d3.forceManyBody().strength(-10).distanceMax(300))
    .force("center", d3.forceCenter(width / 2, height / 2))
    .force("vertical", d3.forceY().strength(0.018))
    .force("horizontal", d3.forceX().strength(0.006));

d3.json(bamConfigJson.bamMainDataLocation, function(error, graph) {
    if (error) throw error;

    var link = svg.append("g")
        .attr("class", "links")
        .selectAll("line")
        .data(graph.edges)
        .enter().append("line")
        .attr("stroke-width", function(d) {
            return Math.sqrt(d.value);
        });

    var node = svg.append("g")
        .attr("class", "nodes")
        .selectAll("circle")
        .data(graph.nodes)
        .enter().append("circle")
        .attr("r", function(d) {
            //check if there is a size attribute for the nodes. If not, default to minimum size
            if (typeof(d.size) !== 'undefined') {
                var sizer = d.size / bamConfigJson.bamSizeDivider;
                if (sizer < bamConfigJson.bamNodeMinSize) {
                    return bamConfigJson.bamNodeMinSize;
                }
                if (sizer > bamConfigJson.bamNodeMaxSize) {
                    return bamConfigJson.bamNodeMaxSize;
                } else {
                    return sizer;
                }
            } else {
                return bamConfigJson.bamNodeMinSize;
            }
        })
        .attr("fill", function(d) {
            return color(d.color);
        })
        .attr("title", function(d) {
            return d.title;
        })
        .call(d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended))
        .on("mouseover", function(d) {
            div.transition()
                .style("opacity", .9);
            var titleHtml = '<center><b>' + d.attributes[bamConfigJson.bamToolTipTitle] + '</b></center>';
            var attributesHtml = '<br />';

            for (var key in bamConfigJson.bamD3ToolTipAtributes) {
                if (bamConfigJson.bamD3ToolTipAtributes.hasOwnProperty(key)) {
                    if (d.attributes[key]) {
                        //we may want to call the attribute by a different title, which is supplied in the config file
                                                attributesHtml = attributesHtml + '<br />';
                        attributesHtml = attributesHtml + bamConfigJson.bamD3ToolTipAtributes[key];
                        attributesHtml = attributesHtml + ' ' + d.attributes[key];
                    }
                }
            }


            //now to iterate through the array of attributes desired in the tooltip
            div.html(titleHtml + attributesHtml)
                .style("left", (d3.event.pageX) + "px")
                .style("top", (d3.event.pageY - 28) + "px")
                // change the tooltip background to the node color
                .style("background", color(d.color));
        })
        .on("mouseout", function(d) {
            div.transition()
                .style("opacity", 0);
        })
        .on("click", function(d) {
            styleD3Selection(d);
            createInfoMasthead(d, true);

            if (rightsideSlideToggle == false) {
                $("#right-side-panel").animate({
                    right: "0",
                }, 500, function() {
                    // Animation complete.
                    rightsideSlideToggle = true;
                    $("#right-btn-slide-toggle").html('&#9654;');
                });
            }
        });

    node.append("title")
        .text(function(d) {
            return d.attributes.name;
        });

    // Highlight only the nodes that are connected to the nide that is clicked
    // Create an array logging what is connected to what
    // here we link by id, not index!
    var linkedByIndex = {};
    for (i = 0; i < graph.nodes.length; i++) {
        linkedByIndex[graph.nodes[i].id + "," + graph.nodes[i].id] = 1;
    };
    graph.edges.forEach(function(d) {
        linkedByIndex[d.source + "," + d.target] = 1;
    });
    //This function looks up whether a pair are neighbours
    function neighboring(a, b) {
        return linkedByIndex[a.id + "," + b.id];
    }

    // see http://stackoverflow.com/questions/22941796/attaching-onclick-event-to-d3-chart-background
    $("svg:not('.node')").on("click", function() {
        returnOpacity();
    });

    // now for the simulation / force layout 
    simulation
        .nodes(graph.nodes)
        .on("tick", ticked);

    simulation.force("link")
        .links(graph.edges);

    simulation.alphaDecay(.0107);

    // the ticked function 
    function ticked() {
        link
            .attr("x1", function(d) {
                return d.source.x;
            })
            .attr("y1", function(d) {
                return d.source.y;
            })
            .attr("x2", function(d) {
                return d.target.x;
            })
            .attr("y2", function(d) {
                return d.target.y;
            });

        node
            .attr("cx", function(d) {
                return d.x;
            })
            .attr("cy", function(d) {
                return d.y;
            });
    }

    //pulling this out here due to index issue
    for (i = 0; i < graph.nodes.length; i++) {
        var nodeListHolder = [];
        nodeListHolder.push(graph.nodes[i].attributes.name);
        nodeListHolder.push(graph.nodes[i].index);
        nodeList.push(nodeListHolder);
    }

    function createInfoMasthead(d, prop) {

        connectedNodes(d);
        if (prop == true) {
            // the stop is necessary - see http://stackoverflow.com/questions/22941796/attaching-onclick-event-to-d3-chart-background  
            d3.event.stopPropagation();
        }
        //set html in side panel
        var rSideHtmml = '';

        if (d.attributes[bamConfigJson.bamD3AttributeImage] != '') {
            rSideHtmml = rSideHtmml + '<img src="' + d.attributes[bamConfigJson.bamD3AttributeImage] + '" class="right-title-image" alt="wikiimage">';

        } else {
            rSideHtmml = rSideHtmml + '<img src="' + bamConfigJson.bamD3AttributeImageDefault + '" class="right-title-image" alt="wikilogo">';
        }

        rSideHtmml = rSideHtmml + '<br /><b>' + d.attributes.name + '</b>';

        if (d.attributes[bamConfigJson.bamD3AttributeTitleLink] != '') {
            rSideHtmml = rSideHtmml + '<br /><a href="' + d.attributes[bamConfigJson.bamD3AttributeTitleLink] + '" target="_blank">' + d.attributes[bamConfigJson.bamD3AttributeTitleLink] + '</a><br /><br />';
        }

        $('#right-side-headline').html(rSideHtmml);
        var rightTextMainHolder = '<b>Network Statistics:</b> </br>';
        // go through our keys and attributes that we want to display
        // if the key is in our config file and in our data, display it
        for (var key in d.attributes) {
            if (d.attributes.hasOwnProperty(key)) {
                if (bamConfigJson.bamD3Attributes[key]) {
                    //we may want to call the attribute by a different title, which is supplied in the config file
                    rightTextMainHolder = rightTextMainHolder + bamConfigJson.bamD3Attributes[key] + ': ' + d.attributes[key] + '<br />';
                }
            }
        }
        $('#right-side-text-main').html(rightTextMainHolder);
    }


    function connectedNodes(d) {

        connectionsSearchTable.clear();

        var connectedList = [];
        var linkStructureHolder = [];



        if (toggle == 0) {
            //Reduce the opacity of all but the neighbouring nodes
            node.style("opacity", function(o) {
                if (neighboring(d, o) == 1 || neighboring(o, d) == 1) {
                    // we do not want to list a self-connection
                    if (o != d) {
                        //connectedList[o.index] = o.attributes.name;
                        var connectedListHolder = [];

                        connectedListHolder.push(o.attributes.name);
                        connectedListHolder.push(o.index);
                        connectedList.push(connectedListHolder);
                    }
                    return 1;
                } else {
                    return .1;
                }
            });

            link.style("opacity", function(o) {
                //expanded from the example to add more functionality
                if (d.index == o.source.index || d.index == o.target.index) {
                    // console.log(o);
                    var tempLinkStructure = {};
                    tempLinkStructure.sourceTitle = o.source.label;
                    tempLinkStructure.sourceIndex = o.source.index;
                    tempLinkStructure.targetTitle = o.target.label;
                    tempLinkStructure.targetIndex = o.target.index;
                    tempLinkStructure.connectionType = o.attributes.relationship;

                    linkStructureHolder.push(tempLinkStructure);
                    return 1;
                } else {
                    return .1;
                }
            });

            if (linkStructureHolder.length > 0) {
                connectionsSearchTable.rows.add(linkStructureHolder);
            }

            connectionsSearchTable.draw();

            //unbind the table before we reconstruct the bindings - this was adding issues earlier
            $('#right-side-text-connected-list tbody').off('click');


            $('#right-side-text-connected-list tbody').on('click', 'tr', function() {
                var data = connectionsSearchTable.row(this).data();
                console.log(data);
                if (data.sourceIndex != d.index) {
                    buttonIndex = data.sourceIndex;
                } else {
                    buttonIndex = data.targetIndex;

                }
                svg.selectAll("circle").each(function(d2, i) {

                    if (buttonIndex == d2.index) {
                        createInfoMasthead(d2, false);
                        connectedNodes(d2);
                        zoomToD3Selection(d2);
                    }
                });
            });

        } else {
            //Put them back to base opacity
            returnOpacity();
        }
    }

//
//Functions
//

    function returnOpacity() {
        node.style("opacity", 1);
        link.style("opacity", 1);
        toggle = 0;
    }

    //as the node can be clicked or selected in different ways, this function styles whatever selection we have
    function styleD3Selection(d) {
        //change the border to show which one is selected
        d.clicked = true;
        svg.selectAll("circle")
            .style("stroke-width", function(d) {
                if (d.clicked == true) {
                    return 2;
                }
            })
            .style("stroke", function(d) {
                if (d.clicked == true) {
                    return "red";
                }
            });
        d.clicked = false;
    }

    //zooms to a selected node - we also style it as selected
    function zoomToD3Selection(d) {
        //style selection
        styleD3Selection(d);

        if (centeredNode !== d) {
            x = d.x;
            y = d.y;
            k = 3;
            centeredNode = d;
        } else {
            x = width / 2;
            y = height / 2;
            k = 1;
            centeredNode = null;
        }

        //now move the viewport
        svg.transition()
            .duration(450)
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
            .style("stroke-width", 1.5 / k + "px");
    }

    //escape key closes the attribute pane and restores the network.
    $(document).keyup(function(e) {
        if (e.keyCode == 27) { // escape key maps to keycode '27'
            returnOpacity();
        }
    });

    zoom.scaleTo(svg, 1);

    //now put the node list into our search location in the main menu panel
    var mainSearchTable = $('#nameSearch').DataTable({
        "bLengthChange": false,
        "pageLength": 5,
        data: nodeList,
        columns: [{
            title: "Name",
            className: "dtColumnHeader"
        }]
    });


    $('#nameSearch tbody').on('click', 'tr', function() {
        var buttonIndex = mainSearchTable.row(this).data()[1];
        svg.selectAll("circle").each(function(d, i) {
            if (buttonIndex == d.index) {
                createInfoMasthead(d, false);
                connectedNodes(d);

                zoomToD3Selection(d);

                if (rightsideSlideToggle == false) {
                    $("#right-side-panel").animate({
                        right: "0",
                    }, 500, function() {
                        // Animation complete.
                        rightsideSlideToggle = true;
                        $("#right-btn-slide-toggle").html('&#9654;');
                    });
                }
            }
        });
    });

    //end of d3 data
});

function dragstarted(d) {
    if (!d3.event.active) simulation.alphaTarget(0.3).restart();
    d.fx = d.x;
    d.fy = d.y;
}

function dragged(d) {
    d.fx = d3.event.x;
    d.fy = d3.event.y;
}

function dragended(d) {
    if (!d3.event.active) simulation.alphaTarget(0.3);
    d.fx = null;
    d.fy = null;
}


function zoomed() {
    svg.attr("transform", d3.event.transform);
}