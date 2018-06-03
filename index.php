<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">    <script src="//d3js.org/d3.v3.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="//d3js.org/topojson.v1.min.js"></script>
    <?php include_once 'DbConnection.php'; ?>
</head>

<body>
    <div id="navbar">
        <div id="logo">
            <img class="smiles" src="img/Positive.png">
            <img class="smiles" src="img/Neutral.png">
            <img class="smiles" src="img/Negative.png">
        </div>
        <a href="#home" id="site-name">Tourism Sentiment Analysis</a>
    </div>

    <div class="content">
        <div class="sidenav">
            <div class="filter">
                <div class="filter-name">Filtri:</div>
            </div>

            <div class="filter" id="filter-sentiment">
                <div class="filter-name"> Sentiment: <br> </div>
                <div class="filter-content" >
                    <div data-toggle="buttons" id="toggles-sentiment">
                        <label class="btn btn-primary">
                            <input type="radio" hidden id="option1" autocomplete="off" name="radio-sentiment" value="tutti" checked> Tutti
                        </label>
                        <label class="btn btn-success">
                            <input type="radio" hidden id="option2" autocomplete="off" name="radio-sentiment" value="positivo" > Positivo
                        </label>
                        <label class="btn btn-warning">
                            <input type="radio" hidden id="option3" autocomplete="off" name="radio-sentiment" value="neutrale" > Neutro
                        </label>
                        <label class="btn btn-danger">
                            <input type="radio" hidden id="option4" autocomplete="off" name="radio-sentiment" value="negativo" > Negativo
                        </label>
                    </div>
                </div>
            </div>
            <div class="filter">
                <div class="filter-name"> Periodo: <br></div>
                <div class="filter-content">
                    <select id="sel-month-datefilter" class="form-control">
                        <option value="0" disabled selected> -Seleziona Mese- </option>
                        <option value="1">Gennaio</option>
                        <option value="2">Febbraio</option>
                        <option value="3">Marzo</option>
                        <option value="4">Aprile</option>
                        <option value="5">Maggio</option>
                        <option value="6">Giugno</option>
                        <option value="7">Luglio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Settembre</option>
                        <option value="10">Ottobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Dicembre</option>
                    </select>
                    <select id="sel-year-datefilter" class="form-control">
                        <option value="0" disabled selected> -Seleziona Anno- </option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                    </select>
                    <input type="button" id="btn-datefilter-apply" class="btn btn-sm btn-secondary" value="Applica">
                    <input type="button" id="btn-datefilter-cancel" class="btn btn-sm btn-secondary" value="Cancella">
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
                    <tr><td id="info-filter" align="center"></td></tr>
                    <tr><td id="info-data" align="center"></td></tr>
                </table>
            </div>
        </div>

        <div id="json"></div>

        <div id="altriStati">
            <div id='dashboard'>

            </div>
        </div>

    <script>
        /* -------------- Gestione Dashboard Paesi Esteri ---------------- */
        const monthNames = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
            "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"
        ];

        function dashboard(id, fData){
            var dash = {};

            var barColor = '#07a0aa';
            function segColor(c){ return {positivo:"#00C200", neutrale:"#ffab1c", negativo:"#FF0000"}[c]; }

            // compute total for each state.
            fData.forEach(function(d){d.total=d.freq.positivo+d.freq.negativo+d.freq.neutrale;});

            // function to handle histogram.
            function histoGram(fD){
                var hG={},    hGDim = {t: 60, r: 0, b: 150, l: 0};
                hGDim.w = 600 - hGDim.l - hGDim.r, //1000
                    hGDim.h = 450 - hGDim.t - hGDim.b;

                //create svg for histogram.
                var hGsvg = d3.select(id).append("svg")
                    .attr("width", hGDim.w + hGDim.l + hGDim.r)
                    .attr("height", hGDim.h + hGDim.t + hGDim.b).append("g")
                    .attr("transform", "translate(" + hGDim.l + "," + hGDim.t + ")");

                // create function for x-axis mapping.
                var x = d3.scale.ordinal().rangeRoundBands([0, hGDim.w], 0.1)
                    .domain(fD.map(function(d) { return d[0]; }));

                // Add x-axis to the histogram svg.
                hGsvg.append("g").attr("class", "x axis")
                    .attr("transform", "translate(0," + hGDim.h + ")")
                    .call(d3.svg.axis().scale(x).orient("bottom"));

                // mette le labels in verticale
                var labels = d3.selectAll(".tick").selectAll("text");
                labels.attr("transform", "rotate(90)").attr("style","").attr("y", "-3").attr("x", "10");


                // Divide le labels troppo lunghe in più righe
                /*var labels = d3.selectAll(".tick").selectAll("text");
                labels.each(function () {
                    var strLabel = d3.select(this).html();
                    if (strLabel.length > 10){
                        var words = strLabel.split(" ");
                        d3.select(this).html("");
                        for (var i = 0; i < words.length; i++){
                            d3.select(this).append("tspan").attr("x", "0").attr("dy", "1em").html(words[i]);
                        }
                    }

                });*/

                // Create function for y-axis map.
                var y = d3.scale.linear().range([hGDim.h, 0])
                    .domain([0, d3.max(fD, function(d) { return d[1]; })]);

                // Create bars for histogram to contain rectangles and freq labels.
                var bars = hGsvg.selectAll(".bar").data(fD).enter()
                    .append("g").attr("class", "bar");

                //create the rectangles.
                bars.append("rect")
                    .attr("x", function(d) { return x(d[0]); })
                    .attr("y", function(d) { return y(d[1]); })
                    .attr("width", x.rangeBand())
                    .attr("height", function(d) { return hGDim.h - y(d[1]); })
                    .attr('fill',barColor)
                    .on("mouseover",mouseover)// mouseover is defined below.
                    .on("mouseout",mouseout);// mouseout is defined below.

                //Create the frequency labels above the rectangles.
                bars.append("text").text(function(d){ return d3.format(",")(d[1])})
                    .attr("x", function(d) { return x(d[0])+x.rangeBand()/2; })
                    .attr("y", function(d) { return y(d[1])-5; })
                    .attr("text-anchor", "middle");

                function mouseover(d){  // utility function to be called on mouseover.
                    // filter for selected state.
                    var st = fData.filter(function(s){ return s.State == d[0];})[0],
                        nD = d3.keys(st.freq).map(function(s){ return {type:s, freq:st.freq[s]};});

                    // call update functions of pie-chart and legend.
                    pC.update(nD);
                    leg.update(nD);
                }

                function mouseout(d){    // utility function to be called on mouseout.
                    // reset the pie-chart and legend.
                    pC.update(tF);
                    leg.update(tF);
                }

                // create function to update the bars. This will be used by pie-chart.
                hG.update = function(nD, color){
                    // update the domain of the y-axis map to reflect change in frequencies.
                    y.domain([0, d3.max(nD, function(d) { return d[1]; })]);

                    // Attach the new data to the bars.
                    var bars = hGsvg.selectAll(".bar").data(nD);

                    // transition the height and color of rectangles.
                    bars.select("rect").transition().duration(500)
                        .attr("y", function(d) {return y(d[1]); })
                        .attr("height", function(d) { return hGDim.h - y(d[1]); })
                        .attr("fill", color);

                    // transition the frequency labels location and change value.
                    bars.select("text").transition().duration(500)
                        .text(function(d){ return d3.format(",")(d[1])})
                        .attr("y", function(d) {return y(d[1])-5; });
                }
                return hG;
            }

            // function to handle pieChart.
            function pieChart(pD){
                var pC ={},    pieDim ={w:250, h: 250};
                pieDim.r = Math.min(pieDim.w, pieDim.h) / 2;

                // create svg for pie chart.
                var piesvg = d3.select(id).append("svg")
                    .attr("width", pieDim.w).attr("height", pieDim.h)./*attr("style","vertical-align:top").*/append("g")
                    .attr("transform", "translate("+pieDim.w/2+","+pieDim.h/2+")");

                // create function to draw the arcs of the pie slices.
                var arc = d3.svg.arc().outerRadius(pieDim.r - 10).innerRadius(0);

                // create a function to compute the pie slice angles.
                var pie = d3.layout.pie().sort(null).value(function(d) { return d.freq; });

                // Draw the pie slices.
                piesvg.selectAll("path").data(pie(pD)).enter().append("path").attr("d", arc)
                    .each(function(d) { this._current = d; })
                    .style("fill", function(d) { return segColor(d.data.type); })
                    .on("mouseover",mouseover).on("mouseout",mouseout);

                // create function to update pie-chart. This will be used by histogram.
                pC.update = function(nD){
                    piesvg.selectAll("path").data(pie(nD)).transition().duration(500)
                        .attrTween("d", arcTween);
                }
                // Utility function to be called on mouseover a pie slice.
                function mouseover(d){
                    // call the update function of histogram with new data.
                    hG.update(fData.map(function(v){
                        return [v.State,v.freq[d.data.type]];}),segColor(d.data.type));
                }
                //Utility function to be called on mouseout a pie slice.
                function mouseout(d){
                    // call the update function of histogram with all data.
                    var activeSentimentFilter = d3.select("#toggles-sentiment").selectAll(".active").filter(function (d, i) { return i === 0; });
                    if (activeSentimentFilter.size() > 0){
                        var sentiment = activeSentimentFilter.select("input").filter(function (d, i) { return i === 0; }).node().value;
                        switch (sentiment) {
                            case "positivo": dash.changePos(); break;
                            case "neutrale": dash.changeNeu(); break;
                            case "negativo": dash.changeNeg(); break;
                            default:
                                hG.update(fData.map(function(v){
                                    return [v.State,v.total];}), barColor);
                        }
                    } else {
                        hG.update(fData.map(function (v) {
                            return [v.State, v.total];
                        }), barColor);
                    }
                }
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
                var legend = d3.select(id).append("table").attr('class','legend');

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
                }

                function getLegend(d,aD){ // Utility function to compute percentage.
                    return d3.format("%")(d.freq/d3.sum(aD.map(function(v){ return v.freq; })));
                }

                return leg;
            }

            // calculate total frequency by segment for all state.
            var tF = ['positivo','neutrale','negativo'].map(function(d){
                return {type:d, freq: d3.sum(fData.map(function(t){ return t.freq[d];}))};
            });

            // calculate total frequency by state for all segment.
            var sF = fData.map(function(d){return [d.State,d.total];});

            var hG = histoGram(sF), // create the histogram.
                pC = pieChart(tF), // create the pie-chart.
                leg= legend(tF);  // create the legend.

            dash.update = function (newData) {
                fData = newData;
                fData.forEach(function(d){d.total=d.freq.positivo+d.freq.negativo+d.freq.neutrale;});

                tF = ['positivo','neutrale','negativo'].map(function(d){
                    return {type:d, freq: d3.sum(fData.map(function(t){ return t.freq[d];}))};
                });

                // call update functions
                hG.update(fData.map(function(v){
                    return [v.State,v.total];}), barColor);
                pC.update(tF);
                leg.update(tF);
            };

            dash.changePos = function () {
                hG.update(fData.map(function(v){
                    return [v.State,v.freq['positivo']];}), segColor('positivo'))
            };
            dash.changeNeg = function () {
                hG.update(fData.map(function(v){
                    return [v.State,v.freq['negativo']];}), segColor('negativo'))
            };
            dash.changeNeu = function () {
                hG.update(fData.map(function(v){
                    return [v.State,v.freq['neutrale']];}), segColor('neutrale'))
            };

            dash.normal = function () {
                hG.update(fData.map(function(v){
                        return [v.State,v.total];}), barColor);
            };

            return dash;
        }

        var freqDataState=<?php echo getFrequencyAbroadCountries(DbConnection()); ?>;
        //console.log("freqdatastate", freqDataState);
        var dash = dashboard('#dashboard',freqDataState);
    </script>

    <script>
        /*---------------- Gestione Mappa e Diagramma a Torta ----------------*/
        function initMap(){
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("json").innerHTML = this.responseText;
                    var response = JSON.parse(this.responseText);
                    drawMap(response);
                }
            };
            xmlhttp.open("GET", "get_data.php");
            xmlhttp.send();
        }

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

        d3.json("json/regioni3.json", function (error, it) {
            if (error) throw error;

            g.append("g")
                .attr("id", "states")
                .selectAll("path")
                .data(topojson.feature(it, it.objects.sub).features)
                .enter().append("path")
                .attr("d", path)
                .attr("id", function (d) {
                    return d.properties.name.toLowerCase()
                })
                .attr("fill", "#07a0aa")
                .on("click", clicked);

            g.append("path")
                .datum(topojson.mesh(it, it.objects.sub, function (a, b) {
                    return a === b;
                }))
                .attr("id", "state-borders")
                .attr("d", path);
            //console.log("fdata", fData);

            //updateMapColors(fData);

        });


        var mapColors = {positivo:["#80FF80", "#41FF32", "#00C200", "#004200" ],
            neutrale:["#fff682", "#ffe031", "#ffab1c", "#e08e1c" ],
            negativo:["#FF9F71", "#FF0000", "#E10000", "#C20000" ]};

        // function to handle legend.
        function mapLegend(lD){
            var soglie = ["0-25%", "26-50%", "51-75%", "76-100%"];

            var leg = {};

            // create table for legend.
            var legend = d3.select("#filter-sentiment").select(".filter-content").append("div").attr('id','map-legend').append("table");
            

            // create one row per segment.
            var tr = legend.append("tbody").selectAll("tr").data(lD).enter().append("tr");

            // create the first column for each segment.
            tr.append("td").append("svg").attr("width", '16').attr("height", '16').append("rect").attr("class", "map-legend-color")
                .attr("width", '16').attr("height", '16')
                .attr("fill",function(d){ return d; });

            // create the second column for each segment.
            tr.data(soglie).append("td").text(function(d){ return d;});

            leg.update = function(nD){

                d3.select("#map-legend").attr("style", "max-height: 10em;");

                // update the data attached to the row elements.
                var l = legend.select("tbody").selectAll("tr").data(nD);

                //update the colors
                l.select(".map-legend-color").attr("fill", function (d) {
                   return d;
                });
            };

            leg.hide = function () {
                d3.select("#map-legend").attr("style", "max-height: 0;");
            };

            return leg;
        }




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

                document.getElementById(st.State).classList.add("selected");


                //visualizza il nome della regione
                document.getElementById("info-regione").innerText =  d.properties.name.toString();

                // call update functions of pie-chart and legend.
                pC.update(nD);
                pieLeg.update(nD);

            } else {
                x = width / 2;
                y = height / 2;
                k = 1;
                centered = null;

                //cancella il nome della regione
                document.getElementById("info-regione").innerText = "ITALIA";

                pC.update(tF);
                pieLeg.update(tF);
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


        function updateMapColors(fData){

            fData.forEach(function (state) {
                    //console.log("state", state);

                    var pos = state.freq["positivo"];
                    var neu = state.freq["neutrale"];
                    var neg = state.freq["negativo"];
                    var maxSent = getMaxSentiment(pos, neu, neg);
                    var somma = state.freq["positivo"] + state.freq["neutrale"] + state.freq["negativo"];
                    var perc = 0;
                    if (somma > 0) {
                        perc = (maxSent / somma) * 100;
                        document.getElementById(state.State).setAttribute("style", "fill:" + getColor(maxSent, perc));

                    }

                    //console.log("colour", getColor(maxSent, perc));


                }
            )}

        function getMaxSentiment(pos, neu, neg) {
            var maxValue;
            if (pos >= neu) {maxValue = "positivo"} else maxValue = "neutrale";
            if (neg >= neu) {maxValue = "negativo"} else maxValue = "neutrale";
            return maxValue;

        }

        /* INFORMAZIONI */
        var id = "#info-data";

        var barColor = '#07a0aa';
        function segColor(c){ return {positivo:"#00C200", neutrale:"#ffab1c", negativo:"#FF0000"}[c]; }

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
        function pieLegend(lD){
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
                var sum = d3.sum(aD.map(function(v){ return v.freq; }));
                if (sum > 0){
                    return d3.format("%")(d.freq/sum);
                } else {
                    return " ";
                }
            }
            return leg;
        }

        var tF = ['positivo','neutrale','negativo'].map(function(d){
            return {type:d, freq: d3.sum(fData.map(function(t){ return t.freq[d];}))};
        });

        var pC = pieChart(tF); // create the pie-chart.
        var pieLeg= pieLegend(tF);  // create the legend.
        var mapLeg = mapLegend(mapColors["positivo"]);
        mapLeg.hide();


        //Filtro sentiment
        d3.select("#toggles-sentiment").selectAll('.btn').on("click", function(){
            var sentiment = d3.select(this).select("input").filter(function (d, i) {return i === 0}).node().value;
            handleRadioSentiment(sentiment);
        });

        function handleRadioSentiment(sentiment) {

            if (sentiment !== "tutti") {
                fData.forEach(function (state) {
                    var name = state.State;
                    var valore = state.freq[sentiment];
                    var somma = state.freq["positivo"] + state.freq["neutrale"] + state.freq["negativo"];
                    var perc = 0;
                    var selector = '[id=\"' + state.State + '\"]';
                    console.log(selector);
                    if (somma > 0) {
                        perc = (valore / somma) * 100;
                        //console.log(state.State);

                        d3.select(selector).transition().duration(500).attr("fill", getColor(sentiment, perc));
                        //document.getElementById(state.State).setAttribute("style", "fill:" + getColor(sentiment, perc));
                    } else {
                        d3.select(selector).transition().duration(500).attr("fill", getColor(sentiment, 0));
                        //document.getElementById(state.State).setAttribute("style", "fill:" + getColor(sentiment, 0));
                    }
                    mapLeg.update(mapColors[sentiment]);
                    switch (sentiment) {
                        case "positivo": dash.changePos(); break;
                        case "neutrale": dash.changeNeu(); break;
                        case "negativo": dash.changeNeg(); break;
                    }
                    //console.log(state.State + ": " + valore + " su " + somma + " ("+ perc +")");
                });
            } else {
                console.log("Tutti");
                fData.forEach(function (state) {
                    var selector = '[id=\"' + state.State + '\"]';
                    d3.select(selector).transition().duration(500).attr("fill", "#07a0aa");
                    //document.getElementById(state.State).removeAttribute("style");
                });
                mapLeg.hide();
                dash.normal();
            }

        }

        function getColor(sent, perc){
            if (perc <= 25){
                return mapColors[sent][0];
            } else if (perc <= 50){
                return mapColors[sent][1];
            } else if (perc <= 75){
                return mapColors[sent][2];
            } else {
                return mapColors[sent][3];
            }
        }

        /* Filtro periodo */
        document.getElementById("btn-datefilter-apply").addEventListener("click", function(){ dateFilter(true);});
        document.getElementById("btn-datefilter-cancel").addEventListener("click", function(){ dateFilter(false);});


        function dateFilter(toActive){
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("json").innerHTML = this.responseText;
                    var response = JSON.parse(this.responseText);

                    // calculate total frequency by segment for all state.
                    var newTF = ['positivo','neutrale','negativo'].map(function(d){
                        return {type:d, freq: d3.sum(response.map(function(t){ return t.freq[d];}))};
                    });

                    //console.log(d3.selectAll(".active").attr("id"));
                    var active = d3.select("#states").selectAll(".active");
                    if (active.size() > 0){
                        var st = response.filter(function (s) {
                                return s.State == active.attr("id");
                            })[0],
                            nD = d3.keys(st.freq).map(function (s) {
                                return {type: s, freq: st.freq[s]};
                            });
                        newTF = nD;
                    }
                    updateData(response);

                    var activeSentimentFilter = d3.select("#toggles-sentiment").selectAll(".active").filter(function (d, i) { return i === 0; });
                    if (activeSentimentFilter.size() > 0){
                        var sentiment = activeSentimentFilter.select("input").filter(function (d, i) { return i === 0; }).node().value;
                        handleRadioSentiment(sentiment);
                    }

                    pieLeg.update(newTF);
                    pC.update(newTF);
                    dateFilter2(toActive);
                }
            };
            if (toActive){
                var selMonth = document.getElementById("sel-month-datefilter");
                var month = parseInt(selMonth.options[selMonth.selectedIndex].value);
                var selYear = document.getElementById("sel-year-datefilter");
                var year = parseInt(selYear.options[selYear.selectedIndex].value);

                d3.select("#info-filter").html(monthNames[month - 1] + " " + year);

                if (month !== 0 && year !== 0 ){
                    xmlhttp.open("GET", "get_data.php?month=" + month + "&year=" + year);
                    xmlhttp.send();
                }
            } else {
                d3.select("#info-filter").html("");

                xmlhttp.open("GET", "get_data.php");
                xmlhttp.send();
            }
        }

        function updateData(nD){
            fData = nD;

            var newTF = ['positivo','neutrale','negativo'].map(function(d){
                return {type:d, freq: d3.sum(nD.map(function(t){ return t.freq[d];}))};
            });

            tF = newTF;
        }


        function dateFilter2(toActive) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("json").innerHTML = this.responseText;
                    var response = JSON.parse(this.responseText);

                    dash.update(response);
                }
            };

            if (toActive){
                var selMonth = document.getElementById("sel-month-datefilter");
                var month = parseInt(selMonth.options[selMonth.selectedIndex].value);
                var selYear = document.getElementById("sel-year-datefilter");
                var year = parseInt(selYear.options[selYear.selectedIndex].value);

                if (month !== 0 && year !== 0 ){
                    xmlhttp.open("GET", "get_abroad_data.php?month=" + month + "&year=" + year);
                    xmlhttp.send();
                }
            } else {
                d3.select("#info-filter").html("");

                xmlhttp.open("GET", "get_abroad_data.php");
                xmlhttp.send();
            }
        }

    }
    </script>

    <script>
        /* --------------------------NAVBAR ----------------------------------- */

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

    </div>
</body>

<script>
    initMap();
</script>

</html>
