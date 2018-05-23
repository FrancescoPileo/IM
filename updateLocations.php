<!DOCTYPE html>
<html>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <?php
        include_once 'DbConnection.php';

        $input = getIdLatLon(DbConnection());
    ?>
</head>
<body>
    <input type="button" value="Localizza" id="submit">
    <table style="width:100%" id="table">
        <tr>
            <th>Id</th>
            <th>Lat</th>
            <th>Lon</th>
            <th>Stato</th>
            <th>Regione</th>
            <th>Provincia</th>
            <th>Città</th>
            <th>N</th>
        </tr>
        <?php
            for ($i = 0; $i < count($input); $i++){
                echo "<tr>";
                echo "<td>" . $input[$i][0] . "</td>"
                    . "<td>" . $input[$i][1] . "</td>"
                    . "<td>" . $input[$i][2] . "</td>"
                    . "<td id = \"s" . $input[$i][0] . "\"></td>"
                    . "<td id = \"r" . $input[$i][0] . "\"></td>"
                    . "<td id = \"p" . $input[$i][0] . "\"></td>"
                    . "<td id = \"c" . $input[$i][0] . "\"></td>"
                    . "<td>" . $i . "</td>";
                echo "</tr>";
            }
        ?>
    </table>

    <script>
        var geocoder;
        var rows = document.getElementById("table").rows;
        var nextRow = 3010; //1
        var delay = 1000;

        function init() {
            geocoder = new google.maps.Geocoder();

            document.getElementById('submit').addEventListener('click', function () {
                theNext();
            });
        }

        function theNext() {
            if (nextRow < 4000) {
                setTimeout('getLocation("'+ rows[nextRow].cells[0].innerHTML.toString() +
                    '", "' + rows[nextRow].cells[1].innerHTML.toString() +
                    '", "' + rows[nextRow].cells[2].innerHTML.toString() + '",theNext)', delay);
                nextRow++;
            } else {
                console.log("Finito");
            }
        }



        function getLocation(id, lat, lon, next){
            var country = null;
            var region = null;
            var province = null;
            var city = null;
            var latlng = {lat: parseFloat(lat), lng: parseFloat(lon)};
            geocoder.geocode({'location': latlng}, function(results, status){
                if (status === 'OK'){
                    if (results[0]){
                        results[0].address_components.forEach(function (entry) {
                            switch(entry.types[0]) {
                                case "country" :
                                    country = entry.long_name.toLowerCase();
                                    document.getElementById("s" + id).innerText = country;
                                    break;
                                case "administrative_area_level_1":
                                    region = entry.long_name.toLowerCase();
                                    document.getElementById("r" + id).innerText = region;
                                    break;
                                case "administrative_area_level_2":
                                    province = entry.short_name.toLowerCase();
                                    document.getElementById("p" + id).innerText = province;
                                    break;
                                case "administrative_area_level_3": //case "locality":
                                    city = entry.long_name.toLowerCase();
                                    document.getElementById("c" + id).innerText = city;
                                    break;
                            }
                        });
                        update(id, country, region, province, city);
                    }
                } else if (status === 'OVER_QUERY_LIMIT'){
                    nextRow--;
                    //delay++;
                    console.log('Geocoder failed due to: ' + status + ' on ' + id);
                }
                next();
            });
        }

        function update(id, country, region, province, city)
        {

            $.ajax({
                url: 'update.php',
                type: 'post',
                data: 'id='+id+'&country='+country+'&region='+region+'&province='+province+'&city='+city,
                success: function(output)
                {
                    //console.log("Insert ok");
                }, error: function()
                {
                    console.log("Insert FAILLLLL!");
                }
            });

        }


    </script>


    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEM2YvE9T6KXE9vn4ErYss3Pwi0_XCr-I&callback=init">

    </script>



</body>
