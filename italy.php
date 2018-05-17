<!DOCTYPE html>
<meta charset="utf-8">
<style>
    .map {fill: #666; stroke: #f5f5f5;}
    .border_map {stroke: darkseagreen; stroke-width: 2px;}
    .regione_map { stroke:darkcyan; stroke-width: 2px;}
</style>
<html>
<body>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://d3js.org/topojson.v1.min.js"></script>
<script>

    var width = 960,
        height = 500;


    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height);

    d3.json("itx.json", function(error, it) {

        var projection = d3.geo.albers()
            .center([0, 41])
            .rotate([347, 0])
            .parallels([35, 45])
            .scale(2000)
            .translate([width / 2, height / 2]);

        var subunits = topojson.feature(it, it.objects.sub);

        var path = d3.geo.path()
            .projection(projection);

        // draw border with sea
        svg.append("path")
            .datum(topojson.mesh(it, it.objects.sub, function(a, b) { return a === b ; }))
            .attr("class", "border_map")
            .attr("d", path);

        // draw all the features together (no different styles)
        svg.append("path")
            .datum(subunits)
            .attr("class", "map")
            .attr("d", path);

        // draw and style any feature at time
        /*svg.selectAll("path")
        .data(topojson.feature(it, it.objects.sub).features)
        .enter().append("path")
        .attr("class",function(d) { return d.id; })
        .attr("d",path);*/


        //draw TORINO border (i.e. the border of a given feature)
        svg.append("path")
            .datum(topojson.mesh(it, it.objects.sub, function(a, b) { return a.properties.COD_REG !== b.properties.COD_REG; }))
            .attr("class", "regione_map")
            .attr("d", path);
    });
</script>

</body>
</html>