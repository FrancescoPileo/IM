<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="//d3js.org/d3.v3.min.js"></script>
    <script src="//d3js.org/topojson.v1.min.js"></script>

</head>

<body>
    <div id="navbar">
        <a href="#home" id="logo">SentimentLocator</a>
    </div>



    <div class="content">
        <div class="sidenav">
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Clients</a>
            <a href="#">Contact</a>
        </div>

        <div id="geocoder">
            <label for="lat">Latitude</label>
            <input id="lat" type="textbox" value="45.4386">
            <label for="lon">Longitude</label>
            <input id="lon" type="textbox" value="12.3267">
            <input id="submit" type="button" value="Geolocalizza">
            <input id="name" type="textbox">
        </div>

        <div id="map">

        </div>

        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent rutrum purus lectus, nec sagittis tortor volutpat sed. Cras cursus gravida ante sed pretium. Phasellus facilisis diam nec orci dapibus elementum. Maecenas semper tristique accumsan. Nunc hendrerit neque aliquam tortor maximus, eu viverra tortor scelerisque. Duis sit amet bibendum urna. Curabitur faucibus felis vitae libero euismod, nec efficitur lacus convallis. Mauris blandit massa diam, eu consequat felis hendrerit sit amet. Sed facilisis vestibulum nisi, quis elementum enim lobortis sit amet. Suspendisse id tortor non enim rhoncus tempor vel non felis. Aenean aliquet magna non ex commodo ultricies. Pellentesque arcu est, semper id diam eu, vehicula hendrerit nulla. Integer ut tellus erat. Vestibulum sit amet pulvinar massa.

        Aenean lectus sapien, auctor sed arcu non, rutrum tempus sapien. Vestibulum sit amet quam dui. Maecenas convallis metus sit amet dui varius, tincidunt aliquet ligula efficitur. Ut malesuada leo rhoncus purus vulputate, porta placerat libero suscipit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras bibendum enim nibh, eu gravida massa pellentesque eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec et ante semper, posuere leo quis, sagittis dolor. Sed id molestie tellus. Quisque pharetra elit ut nunc pulvinar, a elementum enim mollis.

        Nullam ornare ligula justo, sed pellentesque ligula condimentum ut. Sed condimentum commodo est, nec blandit mauris condimentum non. Aenean massa augue, imperdiet ut elit nec, posuere dapibus ligula. Phasellus venenatis venenatis dolor, ac efficitur lorem vehicula ac. Ut feugiat nisi at lacus ultricies iaculis. Proin hendrerit semper pretium. Proin id magna tristique nunc varius interdum et at nunc. Morbi imperdiet mattis velit et imperdiet. Nunc ante tortor, hendrerit vitae libero et, maximus pharetra libero. Suspendisse malesuada lobortis dolor, et facilisis tellus posuere in. Suspendisse in blandit erat.

        Vivamus sit amet varius dolor, eget rutrum nulla. Nullam iaculis interdum vehicula. Mauris sodales eros sed sem tincidunt pellentesque. Sed ac dolor et velit tincidunt volutpat in eu lectus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Suspendisse tristique justo enim, et consequat dui eleifend eu. Morbi ultrices, risus consequat iaculis convallis, justo magna vehicula lectus, quis pharetra justo lorem nec elit. Phasellus vestibulum quam nec enim efficitur tempor. Cras a venenatis neque, non euismod turpis. Cras nec eros ac metus euismod elementum quis ut ante. Duis orci urna, pellentesque vel velit a, commodo suscipit odio.

        Proin egestas lectus id ligula gravida, at varius purus gravida. Donec nec magna imperdiet, mollis nunc ac, fermentum mauris. Integer et mattis libero, in sagittis erat. Sed vel facilisis eros, eu luctus urna. Nam et urna enim. Suspendisse potenti. Proin eget luctus nunc, eu volutpat sapien. Nulla ut congue diam. Pellentesque ut elit orci. Sed malesuada, arcu ac dignissim accumsan, lectus felis euismod lectus, eu vehicula eros felis id mi. Mauris tristique porttitor nibh, vitae malesuada neque tristique at.


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
        var width = window.innerWidth * 3 / 5 ,
            height = window.innerHeight - 100,
            centered;

        var projection = d3.geo.albers()
            .center([2, 42])
            .rotate([347, 0])
            .parallels([85, 45])
            .scale(2500)
            .translate([width / 2, height / 2]);

        var path = d3.geo.path()
            .projection(projection);

        var svg = d3.select("#map").append("svg")
            .attr("width", width)
            .attr("height", height);

        svg.append("rect")
            .attr("class", "background")
            .attr("width", width)
            .attr("height", height)
            .on("click", clicked);

        var g = svg.append("g");

        d3.json("json/regioni.json", function(error, it) {
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

    <script>
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
</html>
