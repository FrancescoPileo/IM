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
            <th>Regione</th>
        </tr>
        <?php
            for ($i = 0; $i < count($input); $i++){
                echo "<tr>";
                echo "<td>" . $input[$i][0] . "</td><td>"
                    . $input[$i][1] . "</td><td>"
                    . $input[$i][2] . "</td><td id = " . $input[$i][0] . "></td>";
            }
        ?>
    </table>

    <script>
        var geocoder;
        var rows = document.getElementById("table").rows;
        var nextRow = 1;
        var delay = 1000;

        function init() {
            geocoder = new google.maps.Geocoder();

            document.getElementById('submit').addEventListener('click', function () {
                theNext();
            });
        }

        /*function getLocations() {


            var functionSting = 'getLocation(rows, "' +  + '")';

            for (var i=1; i < 1000; i++){
                var cells = rows[i].cells;
                var delay = 5000 * i;
                var functionString =  'getLocation("' + cells[0].innerHTML.toString() +
                    '", "' + cells[1].innerHTML.toString() +
                    '", "' + cells[2].innerHTML.toString() + '")';
                console.log(functionString);
                setTimeout( functionString , 2000 * i);

            }

        }*/

        function theNext() {
            if (nextRow < 100) {
                setTimeout('getLocation("'+ rows[nextRow].cells[0].innerHTML.toString() +
                    '", "' + rows[nextRow].cells[1].innerHTML.toString() +
                    '", "' + rows[nextRow].cells[2].innerHTML.toString() + '",theNext)', delay);
                nextRow++;
            } else {
                console.log("Finito");
            }
        }



        function getLocation(id, lat, lon, next){
            var region = null;
            var latlng = {lat: parseFloat(lat), lng: parseFloat(lon)};
            geocoder.geocode({'location': latlng}, function(results, status){
                if (status === 'OK'){
                    if (results[0]){
                        results[0].address_components.forEach(function (entry) {
                            if (entry.types[0] === "administrative_area_level_1"){
                                region = entry.long_name.toLowerCase();
                                //cell.innerHTML = region;
                                //console.log("id: " + id + "regione:" + region);
                                document.getElementById(id).innerText = region;
                                update(id, region);

                            }
                        });
                    }
                } else if (status === 'OVER_QUERY_LIMIT'){
                    nextRow--;
                    delay++;
                    console.log('Geocoder failed due to: ' + status + ' on ' + id);
                }
                next();
            });
        }

        function update(tweetId, regione)
        {

            $.ajax({
                url: 'update.php',
                type: 'post',
                data: 'tweet_id='+tweetId+'&regione='+regione,
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
