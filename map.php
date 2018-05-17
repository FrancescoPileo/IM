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
            .on("click", clicked);

        g.append("path")
            .datum(topojson.mesh(it, it.objects.sub, function(a, b) { return a === b; }))
            .attr("id", "state-borders")
            .attr("d", path);
    });

    function clicked(d) {
        var x, y, k;

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