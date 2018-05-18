<!DOCTYPE html>
<meta charset="utf-8">
<style>

    .background {
        fill: none;
        pointer-events: all;
    }

    #states {
        fill: #07a0aa;
    }

    #states .active {
        fill: orange;
    }

    #states .selected {
        fill: darkseagreen;
    }

    #state-borders {
        fill: none;
        stroke: #fff;
        stroke-width: 1.5px;
        stroke-linejoin: round;
        stroke-linecap: round;
        pointer-events: none;
    }

</style>
<body>
<script src="//d3js.org/d3.v3.min.js"></script>
<script src="//d3js.org/topojson.v1.min.js"></script>

<div id="geocoder">
    <label for="lat">Latitude</label>
    <input id="lat" type="textbox" value="45.4386">
    <label for="lon">Longitude</label>
    <input id="lon" type="textbox" value="12.3267">
    <input id="submit" type="button" value="Geolocalizza">
    <input id="name" type="textbox">
</div>

<script>


    function init() {
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
    }
</script>


<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEM2YvE9T6KXE9vn4ErYss3Pwi0_XCr-I&callback=init">

</script>

<script>

    var width = window.innerWidth - 100,
        height = window.innerHeight - 100,
        centered;

    var projection = d3.geo.albers()
        .center([0, 41])
        .rotate([347, 0])
        .parallels([35, 45])
        .scale(2000)
        .translate([width / 2, height / 2]);

    var path = d3.geo.path()
        .projection(projection);

    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height);

    svg.append("rect")
        .attr("class", "background")
        .attr("width", width)
        .attr("height", height)
        .on("click", clicked);

    var g = svg.append("g");

    d3.json("itx2.json", function(error, it) {
        if (error) throw error;

        g.append("g")
            .attr("id", "states")
            .selectAll("path")
            .data(topojson.feature(it, it.objects.sub).features)
            .enter().append("path")
            .attr("d", path)
            .attr("name", function(d){ return d.properties.name.toLowerCase()})
            .on("click", clicked);

        g.append("path")
            .datum(topojson.mesh(it, it.objects.sub, function(a, b) { return a === b; }))
            .attr("id", "state-borders")
            .attr("d", path);
    });


    function clicked(d) {
        var x, y, k;

        for (var i = 0; i < document.getElementsByClassName("selected").length > 0; i++) {
            document.getElementsByClassName("selected")[i].classList.remove("selected");
        }

        var regionName = d.properties.name;
        document.getElementById('name').value=regionName;

        if (d && centered !== d) {
            var centroid = path.centroid(d);
            x = centroid[0];
            y = centroid[1];
            k = 4;
            centered = d;
        } else {
            x = width / 2;
            y = height / 2;
            k = 1;
            centered = null;
        }

        g.selectAll("path")
            .classed("active", centered && function(d) { return d === centered; });

        g.transition()
            .duration(750)
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
            .style("stroke-width", 1.5 / k + "px");
    }

</script>