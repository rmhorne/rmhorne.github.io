<div class="navbar" id="navbarContainer">
    <div class="navbar-brand">
        <?php echo $NavBarImageLink; ?><img src="../common-files/images/BAM-icon.png" title="BAM Icon" alt="BAM Icon" class="navbar-image"></a>
        <?php echo $NavBarBrand; ?> </div>
</div>
<br />
<br />
<div class="navbar-under">
</div>
<br />
<div id="mainNav" class="mainNav">
    <div id="mainLeft" class="mainLeft">
        <div id="leftContent" class="leftContent">
        
            <button id="infoButton" class="accordion"><img src="../common-files/images/open-iconic/svg/info.svg" class="accordianImage" alt="info">&nbsp;&nbsp;&nbsp;&nbsp;Application Information</button>
        
            <button id="databaseButton" class="accordion"><img src="../common-files/images/open-iconic/svg/list.svg" class="accordianImage" alt="list">&nbsp;&nbsp;&nbsp;&nbsp;Database</button>

            <button id="timeLineButton" class="accordion accordionExp"><img src="../common-files/images/open-iconic/svg/clock.svg" class="accordianImage" alt="clock">&nbsp;&nbsp;&nbsp;&nbsp;Timeline Controls</button>
            <div class="panel">
                <br/>
                <div id="timeLineSearcher">
                    <label> Start:</label>&nbsp;&nbsp;&nbsp;&nbsp;<input id="timeStart" type="text" name="timeStart" value="" size="10" />

                    <br />
                    <br/>
                    <label> End:</label>&nbsp;&nbsp;&nbsp;&nbsp; <input id="timeEnd" type="text" name="timeEnd" value="" size="10" />
                    <br />

                    <br /> Timeline Interval in years:
                    <br />
                    <input id="timeInterval" class="numbersOnly" type="number" value="1">
                    <br />
                    <br />
                    <button id="timeLineFire">Move Timeline</button>
                    <button id="timeLinePlay">Play</button>
                    <button id="timeLinePause">Pause</button>
                </div>
                <br />

            </div>

            <button id="searchButton" class="accordion accordionExp"><img src="../common-files/images/open-iconic/svg/magnifying-glass.svg" class="accordianImage" alt="magnifying-glass">&nbsp;&nbsp;&nbsp;&nbsp;Search Controls</button>
            <div class="panel">
                <br />
                <label><input type="checkbox" name="timelineConstraintMain" value="timelineConstraintMain" checked>Constrain search to timeline values?</label>
                <br />
                            <br />
            <div class="ui-widget">
                <label for="tags">Search for authors, titles, or places: <br /> </label>
                <input id="tags">
            </div>

                <br />

            </div>

            <button id="textButton" class="accordion accordionExp"><img src="../common-files/images/open-iconic/svg/book.svg" class="accordianImage" alt="book">&nbsp;&nbsp;&nbsp;&nbsp;Text Controls</button>
            <div class="panel">
                <br />

                <p>Coming Soon...</p>
                <br />

            </div>

            <button id="networkButton" class="accordion accordionExp"><img src="../common-files/images/open-iconic/svg/fork.svg" class="accordianImage" alt="networks">&nbsp;&nbsp;&nbsp;&nbsp;Network Controls</button>
            <div class="panel">
            </div>
            
            <button id="exportButton" class="accordion accordionExp"><img src="../common-files/images/open-iconic/svg/cloud-download.svg" class="accordianImage" alt="cloud-download">&nbsp;&nbsp;&nbsp;&nbsp;Export / Download</button>
            <div class="panel">
                <br />

                <p>Coming Soon...</p>
                <br />

            </div>
            <hr />

            <div id="leftTextPane" class="leftTextPane">
            <div id="textControls">
            <center>
                        <br />
            <img src="../common-files/images/open-iconic/svg/media-step-backward.svg" class="accordianImage" alt="media-step-backward" id="textAllBack">
            &nbsp;&nbsp;<img src="../common-files/images/open-iconic/svg/media-skip-backward.svg" class="accordianImage" alt="media-skip-backward" id="textStepBack">
            &nbsp;&nbsp;<img src="../common-files/images/open-iconic/svg/media-skip-forward.svg" class="accordianImage" alt="media-skip-forward" id="textStepForward">
            &nbsp;&nbsp;<img src="../common-files/images/open-iconic/svg/media-step-forward.svg" class="accordianImage" alt="media-step-forward"i d="textAllForward">
                                    </center>

            </div>
            </div>

        </div>
    </div>
    <div id="bigCenter" class="bigCenter">
        <div id="timeline" class="timeline"></div>
        <br /><br /><br />
        <div id="map" class="mapCenter"></div>
        <div id="city-name" class="city-name"></div>

    </div>

</div>