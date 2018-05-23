<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="//d3js.org/d3.v3.min.js"></script>
    <script src="//d3js.org/topojson.v1.min.js"></script>
    <?php include_once 'DbConnection.php'; ?>
</head>

<body>
    <div id="navbar">
        <a href="#home" id="logo">Tourism Sentiment Analysis</a>
    </div>



    <div class="content">
        <div class="sidenav">
            <div class="filter">
                <div class="filter-name">Filtri:</div>
            </div>

            <div class="filter">
                <div class="filter-name"> Sentiment: <br> </div>
                <div class="filter-content">
                    <input type="radio" name="radio-sentiment" value="all" checked>Tutti<br>
                    <input type="radio" name="radio-sentiment" value="pos" >Positivo<br>
                    <input type="radio" name="radio-sentiment" value="neu" >Neutro<br>
                    <input type="radio" name="radio-sentiment" value="neg" >Negativo<br>
                </div>
            </div>
            <div class="filter">
                <div class="filter-name"> Periodo: <br></div>
                <div class="filter-content">
                    <select>
                        <option value=""> </option>
                        <option value="gen">Gennaio</option>
                        <option value="feb">Febbraio</option>
                        <option value="mar">Marzo</option>
                        <option value="apr">Aprile</option>
                        <option value="mag">Maggio</option>
                        <option value="giu">Giugno</option>
                        <option value="lug">Luglio</option>
                        <option value="ago">Agosto</option>
                        <option value="set">Settembre</option>
                        <option value="ott">Ottobre</option>
                        <option value="nov">Novembre</option>
                        <option value="dic">Dicembre</option>
                    </select>
                    <select>
                        <option value=""> </option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                    </select>
                    <input type="button" value="Ok">
                </div>
            </div>
            <a href="#">About</a>
        </div>

        <!-- <div id="geocoder">
            <label for="lat">Latitude</label>
            <input id="lat" type="textbox" value="45.4386">
            <label for="lon">Longitude</label>
            <input id="lon" type="textbox" value="12.3267">
            <input id="submit" type="button" value="Geolocalizza">
            <input id="name" type="textbox">
        </div> -->

        <div id="contenitore">
            <div id="map" class="box">

            </div>
            <div id="info" class="box">
                <table id="info-table">
                    <tr><td id="info-regione" align="center">ITALIA</td></tr>
                    <tr><td id="info-data" align="center"></td></tr>

                </table>

            </div>
        </div>

    <script>
        /* GEOLOCALIZZAZIONE */
       /* function init() {
            var geocoder = new google.maps.Geocoder();

            document.getElementById('submit').addEventListener('click', function () {
                getLocation(geocoder);
            });
        }

        function getLocation(geocoder){
            var lat = document.getElementById('lat').value;
            var lon = document.getElementById('lon').value;
            var resultAddress = document.getElementById('result');

            var latlng = {lat: parseFloat(lat), lng: parseFloat(lon)};
            geocoder.geocode({'location': latlng}, function(results, status){
                if (status === 'OK'){
                    if (results[0]){
                        var region = null;
                        results[0].address_components.forEach(function (entry) {
                            //console.log(entry.types[0] + ": " + entry.long_name.toLowerCase() + " (" + entry.short_name.toLowerCase() + ")");

                            if (entry.types[0] === "administrative_area_level_1"){
                                region = entry.long_name.toLowerCase();
                            }
                        });
                        //window.alert(region + ": " + results[0].address_components[4].types[0]);
                        if (document.getElementsByName(region).length === 1){
                            for (var i = 0; i < document.getElementsByClassName("selected").length > 0; i++) {
                                document.getElementsByClassName("selected")[i].classList.remove("selected");
                            }
                            document.getElementsByName(region)[0].classList.add("selected");
                        } else {
                            window.alert("Problema nel nome della regione");
                        }
                        //resultAddress.setContent(results[0].formatted_address);
                    } else {
                        window.alert('Nessun risultato');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }*/
    </script>

    <script>
        /*MAPPA*/
    function drawMap(fData) {
        var width = window.innerWidth * 3 / 5,
            height = window.innerHeight - 100,
            centered;

        d3.select("#info").attr("style", "height:" + height + "px;padding-top:100px;");

        var projection = d3.geo.albers()
            .center([2, 42])
            .rotate([347, 0])
            .parallels([85, 45])
            .scale(2500)
            .translate([width / 2, height / 2]);

        var path = d3.geo.path()
            .projection(projection);

        var svg = d3.select("#map").attr("width", width).attr("height", height).append("svg")
            .attr("width", width)
            .attr("height", height);

        svg.append("rect")
            .attr("class", "background")
            .attr("width", width)
            .attr("height", height)
            .on("click", clicked);

        var g = svg.append("g");

        d3.json("json/regioni.json", function (error, it) {
            if (error) throw error;

            g.append("g")
                .attr("id", "states")
                .selectAll("path")
                .data(topojson.feature(it, it.objects.sub).features)
                .enter().append("path")
                .attr("d", path)
                .attr("name", function (d) {
                    return d.properties.name.toLowerCase()
                })
                .on("click", clicked);

            g.append("path")
                .datum(topojson.mesh(it, it.objects.sub, function (a, b) {
                    return a === b;
                }))
                .attr("id", "state-borders")
                .attr("d", path);
            console.log("fdata", fData);

        });




        function clicked(d) {
            var x, y, k;

            for (var i = 0; i < document.getElementsByClassName("selected").length > 0; i++) {
                document.getElementsByClassName("selected")[i].classList.remove("selected");
            }

            /*var regionName = d.properties.name;
            document.getElementById('name').value = regionName;*/

            if (d && centered !== d) {
                var centroid = path.centroid(d);
                x = centroid[0];
                y = centroid[1];
                k = 4;
                centered = d;

                // filter for selected state.
                var st = fData.filter(function (s) {
                        return s.State == d.properties.name.toLowerCase();
                    })[0],
                    nD = d3.keys(st.freq).map(function (s) {
                        return {type: s, freq: st.freq[s]};
                    });

                //visualizza il nome della regione
                document.getElementById("info-regione").innerText =  d.properties.name.toString();

                // call update functions of pie-chart and legend.
                pC.update(nD);
                leg.update(nD);

            } else {
                x = width / 2;
                y = height / 2;
                k = 1;
                centered = null;

                //cancella il nome della regione
                document.getElementById("info-regione").innerText = "ITALIA";

                pC.update(tF);
                leg.update(tF);
            }

            g.selectAll("path")
                .classed("active", centered && function (d) {
                    return d === centered;
                });

            g.transition()
                .duration(750)
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
                .style("stroke-width", 1.5 / k + "px");


        }


        /*function updateMapColors(fData){

            fData.forEach(function (state) {
                var pos = state.freq["positivo"];
                var neu = state.freq["neutrale"];
                var neg = state.freq["negativo"];
                var maxSent = getMaxSentiment(pos, neu, neg);
                var somma = state.freq["positivo"] + state.freq["neutrale"] + state.freq["negativo"];
                var perc = 0;
                if (somma > 0) {
                    perc = (maxSent / somma) * 100;
                    document.getElementsByName(state.State)[0].setAttribute("style", "fill:" + getColor(maxSent, perc));
                }
                d3.select("#map").select("path").select(state.name).style("fill" , state.style);
            }
        )}*/

        /* INFORMAZIONI */
        var id = "#info-data";
        var barColor = 'steelblue';
        function segColor(c){ return {positivo:"#00ba63", neutrale:"#e08e1c", negativo:"#ab0600"}[c]; }

        // function to handle pieChart.
        function pieChart(pD){
            var pC ={},    pieDim ={w:250, h: 250};
            pieDim.r = Math.min(pieDim.w, pieDim.h) / 2;

            // create svg for pie chart.
            var piesvg = d3.select(id).append("svg")
                .attr("width", pieDim.w).attr("height", pieDim.h).append("g")
                .attr("transform", "translate("+pieDim.w/2+","+pieDim.h/2+")");

            // create function to draw the arcs of the pie slices.
            var arc = d3.svg.arc().outerRadius(pieDim.r - 10).innerRadius(0);

            // create a function to compute the pie slice angles.
            var pie = d3.layout.pie().sort(null).value(function(d) { return d.freq; });

            // Draw the pie slices.
            piesvg.selectAll("path").data(pie(pD)).enter().append("path").attr("d", arc)
                .each(function(d) { this._current = d; })
                .style("fill", function(d) { return segColor(d.data.type); });

            // create function to update pie-chart. This will be used by histogram.
            pC.update = function(nD){
                piesvg.selectAll("path").data(pie(nD)).transition().duration(500)
                    .attrTween("d", arcTween);
            };
            // Utility function to be called on mouseover a pie slice.

            // Animating the pie-slice requiring a custom function which specifies
            // how the intermediate paths should be drawn.
            function arcTween(a) {
                var i = d3.interpolate(this._current, a);
                this._current = i(0);
                return function(t) { return arc(i(t));    };
            }
            return pC;
        }





        // function to handle legend.
        function legend(lD){
            var leg = {};

            // create table for legend.
            var legend = d3.select("#info-table").append("tr").append("td").append("table").attr('class','legend');

            // create one row per segment.
            var tr = legend.append("tbody").selectAll("tr").data(lD).enter().append("tr");

            // create the first column for each segment.
            tr.append("td").append("svg").attr("width", '16').attr("height", '16').append("rect")
                .attr("width", '16').attr("height", '16')
                .attr("fill",function(d){ return segColor(d.type); });

            // create the second column for each segment.
            tr.append("td").text(function(d){ return d.type;});

            // create the third column for each segment.
            tr.append("td").attr("class",'legendFreq')
                .text(function(d){ return d3.format(",")(d.freq);});

            // create the fourth column for each segment.
            tr.append("td").attr("class",'legendPerc')
                .text(function(d){ return getLegend(d,lD);});

            // Utility function to be used to update the legend.
            leg.update = function(nD){
                // update the data attached to the row elements.
                var l = legend.select("tbody").selectAll("tr").data(nD);

                // update the frequencies.
                l.select(".legendFreq").text(function(d){ return d3.format(",")(d.freq);});

                // update the percentage column.
                l.select(".legendPerc").text(function(d){ return getLegend(d,nD);});
            };

            function getLegend(d,aD){ // Utility function to compute percentage.
                return d3.format("%")(d.freq/d3.sum(aD.map(function(v){ return v.freq; })));
            }
            return leg;
        }

        var tF = ['positivo','neutrale','negativo'].map(function(d){
            return {type:d, freq: d3.sum(fData.map(function(t){ return t.freq[d];}))};
        });

        var pC = pieChart(tF); // create the pie-chart.
        var leg= legend(tF);  // create the legend.


        //Filtro sentiment
        d3.selectAll('[name="radio-sentiment"]').data(["tutti", "positivo", "neutrale", "negativo"]).on("click", handleRadioSentiment);

        function handleRadioSentiment(d) {
            if (d !== "tutti") {
                fData.forEach(function (state) {
                    var valore = state.freq[d];
                    var somma = state.freq["positivo"] + state.freq["neutrale"] + state.freq["negativo"];
                    var perc = 0;
                    if (somma > 0) {
                        perc = (valore / somma) * 100;
                        document.getElementsByName(state.State)[0].setAttribute("style", "fill:" + getColor(d, perc));
                    }
                    console.log(state.State + ": " + valore + " su " + somma + " ("+ perc +")");
                });
            } else {
                console.log("Tutti");
                fData.forEach(function (state) { document.getElementsByName(state.State)[0].removeAttribute("style"); });
            }

        }

        function getColor(sent, perc){

            var colors = {positivo:{25: "#b6fecd", 50: "#97fed1", 75: "#00de82", 100: "#00ba63" },
                            neutrale:{25: "#fff682", 50: "#ffe031", 75: "#ffab1c", 100: "#e08e1c" },
                            negativo:{25: "#ffd6e4", 50: "#ea8bb0", 75: "#ea30a6", 100: "#ab2976" }};

            if (perc <= 25){
                return colors[sent][25];
            } else if (perc <= 50){
                return colors[sent][50];
            } else if (perc <= 75){
                return colors[sent][75];
            } else {
                return colors[sent][100];
            }
        }

    }
    </script>

    <script>
        /*NAVBAR*/

        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {myFunction()};

        // Get the navbar
        var navbar = document.getElementById("navbar");

        // Get the offset position of the navbar
        var sticky = navbar.offsetTop;

        // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function myFunction() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky")
            } else {
                navbar.classList.remove("sticky");
            }
        }
    </script>

    <script>

    </script>
    </div>
</body>

<script>
    /*DATI INFO*/
    var freqData=[
        {State:'piemonte',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "piemonte"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "piemonte"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "piemonte"); ?>}}
        ,{State:"valle d'aosta",freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "valle d'aosta"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "valle d'aosta"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "valle d'aosta"); ?>}}
        ,{State:'lombardia',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "lombardia"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "lombardia"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "lombardia"); ?>}}
        ,{State:'trentino-alto adige',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "trentino-alto adige"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "trentino-alto adige"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "trentino-alto adige"); ?>}}
        ,{State:'veneto',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "veneto"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "veneto"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "veneto"); ?>}}
        ,{State:'friuli venezia giulia',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "friuli venezia giulia"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "friuli venezia giulia"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "friuli venezia giulia"); ?>}}
        ,{State:'liguria',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "liguria"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "liguria"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "liguria"); ?>}}
        ,{State:'emilia-romagna',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "emilia-romagna"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "emilia-romagna"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "emilia-romagna"); ?>}}
        ,{State:'toscana',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "toscana"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "toscana"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "toscana"); ?>}}
        ,{State:'umbria',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "umbria"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "umbria"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "umbria"); ?>}}
        ,{State:'marche',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "marche"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "marche"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "marche"); ?>}}
        ,{State:'lazio',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "lazio"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "lazio"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "lazio"); ?>}}
        ,{State:'abruzzo',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "abruzzo"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "abruzzo"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "abruzzo"); ?>}}
        ,{State:'molise',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "molise"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "molise"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "molise"); ?>}}
        ,{State:'campania',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "campania"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "campania"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "campania"); ?>}}
        ,{State:'puglia',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "puglia"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "puglia"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "puglia"); ?>}}
        ,{State:'basilicata',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "basilicata"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "basilicata"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "basilicata"); ?>}}
        ,{State:'calabria',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "calabria"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "calabria"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "calabria"); ?>}}
        ,{State:'sicilia',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "sicilia"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "sicilia"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "sicilia"); ?>}}
        ,{State:'sardegna',freq:{positivo:<?php echo getFrequencyByRegion(DbConnection(), 'pos', "sardegna"); ?>,
                neutrale:<?php echo getFrequencyByRegion(DbConnection(), 'neu', "sardegna"); ?>,
                negativo:<?php echo getFrequencyByRegion(DbConnection(), 'neg', "sardegna"); ?>}}

    ];

    /*function updateMapColors(fData){

        fData.forEach(function (state) {
            console.log("state", state);

            var pos = state.freq["positivo"];
                var neu = state.freq["neutrale"];
                var neg = state.freq["negativo"];
                var maxSent = getMaxSentiment(pos, neu, neg);
                var somma = state.freq["positivo"] + state.freq["neutrale"] + state.freq["negativo"];
                var perc = 0;
                if (somma > 0) {
                    perc = (maxSent / somma) * 100;
                }
                console.log("colour", getColor(maxSent, perc));

---------------------------- non riesco a prendere la regione e a cambiarli colore in base al sentiment ---------------------

                var array = document.getElementById("states");
                console.log("array", array);
                var regione = state.name == d3.select("#map").select("path").select("states").properties.name.toLowerCase()) {

                }
                d3.select("#map").select("path").select(state.name).style("colour" , getColor(maxSent, perc));

            }
        )}
    function getMaxSentiment(pos, neu, neg) {
        var maxValue;
        if (pos >= neu) {maxValue = "positivo"} else maxValue = "neutrale";
        if (neg >= neu) {maxValue = "negativo"} else maxValue = "neutrale";
        return maxValue;

    }

    function getColor(sent, perc){

        var colors = {positivo:{25: "#b6fecd", 50: "#97fed1", 75: "#00de82", 100: "#00ba63" },
            neutrale:{25: "#fff682", 50: "#ffe031", 75: "#ffab1c", 100: "#e08e1c" },
            negativo:{25: "#ffd6e4", 50: "#ea8bb0", 75: "#ea30a6", 100: "#ab2976" }};

        if (perc <= 25){
            return colors[sent][25];
        } else if (perc <= 50){
            return colors[sent][50];
        } else if (perc <= 75){
            return colors[sent][75];
        } else {
            return colors[sent][100];
        }
    }*/

    drawMap(freqData);
    //updateMapColors(freqData);


</script>

</html>
